//
//  StartViewController.swift
//  Vault
//
//  Created by Carl Burnstein on 10/1/19.
//  Copyright © 2019 CASLS.
//

import UIKit
import AVFoundation
import QRCodeReader
import SideMenu

class StartViewController: UIViewController, UITextFieldDelegate {
    
    var selectedQuest : Quest!
    let appDelegate = UIApplication.shared.delegate as! AppDelegate
    var user : User!
    @IBOutlet weak var qrCodeButton: DesignableButton!
    @IBOutlet weak var startButton: DesignableButton!
    @IBOutlet weak var basicStartCodeField: DesignableTextField!
    @IBOutlet weak var scrollView: UIScrollView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        // Do any additional setup after loading the view.
        startButton.roundCorners(corners: [.topRight, .bottomRight], radius: 4)
        SideMenuManager.default.addPanGestureToPresent(toView: self.view)
        SideMenuManager.default.addPanGestureToPresent(toView: self.navigationController!.navigationBar)
    }
    
    override func viewDidAppear(_ animated: Bool) {
        super.viewDidAppear(animated)
        user = appDelegate.getUser()
        if(user == nil){
            performSegue(withIdentifier: "loginSegue", sender: self)
        }else{
        }
    }
    
    override func viewWillAppear(_ animated: Bool) {
            super.viewWillAppear(animated)
            NotificationCenter.default.addObserver(self, selector: #selector(keyboardWillShow), name:UIResponder.keyboardWillShowNotification, object: nil)
            NotificationCenter.default.addObserver(self, selector: #selector(keyboardWillHide), name:UIResponder.keyboardWillHideNotification, object: nil)
        }
    
    override func viewWillDisappear(_ animated: Bool) {
        NotificationCenter.default.removeObserver(self, name: UIResponder.keyboardWillShowNotification, object: nil)
        NotificationCenter.default.removeObserver(self, name: UIResponder.keyboardWillHideNotification, object: nil)
    }
    
    @objc func keyboardWillShow(notification: NSNotification){
        guard let keyboardFrame = notification.userInfo![UIResponder.keyboardFrameEndUserInfoKey] as? NSValue else { return }
        scrollView.contentInset.bottom = view.convert(keyboardFrame.cgRectValue, from: nil).size.height + 20
        scrollView.scrollIndicatorInsets = scrollView.contentInset
    }
    
    @objc func keyboardWillHide(notification: NSNotification){
        scrollView.contentInset = .zero
        scrollView.scrollIndicatorInsets = scrollView.contentInset
    }
    
    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        self.view.endEditing(true)
        return false
    }
    
    @IBAction func startButtonTouched(_ sender: Any) {
        basicStartCodeField.resignFirstResponder()
        if let code = basicStartCodeField.text{
            if(code == ""){
                self.showErrorMessage(title: "", body: "Please enter a basic start code.", presentationStyle: .top, duration: 3, buttonTapHandler: nil, buttonTitle: nil, image: nil)
            }else{
                ApiController.downloadQuestByCode(code: code, completion: { (success, msg, downloadedQuest) in
                    if(success){
                        self.selectedQuest = downloadedQuest
                        self.performSegue(withIdentifier: "taskSegue", sender: self)
                    }else{
                        self.showErrorMessage(title: "Error", body: msg!, presentationStyle: .top, duration: 5.0, buttonTapHandler: nil, buttonTitle: nil, image: nil)
                    }
                }) 
            }
        }else{
            self.showErrorMessage(title: "", body: "Please enter a basic start code.", presentationStyle: .top, duration: 3, buttonTapHandler: nil, buttonTitle: nil, image: nil)
        }
    }
    
     // MARK: - Navigation
    
     // In a storyboard-based application, you will often want to do a little preparation before navigation
     override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
     // Get the new view controller using segue.destination.
     // Pass the selected object to the new view controller.
        if(segue.identifier == "taskSegue"){
            let tldvc = segue.destination as! TaskPagingViewController
            tldvc.quest = selectedQuest
        }
     }

    
    // Good practice: create the reader lazily to avoid cpu overload during the
    // initialization and each time we need to scan a QRCode
    lazy var readerVC: QRCodeReaderViewController = {
        let builder = QRCodeReaderViewControllerBuilder {
            $0.reader = QRCodeReader(metadataObjectTypes: [.qr], captureDevicePosition: .back)
            
            // Configure the view controller (optional)
            $0.showTorchButton        = false
            $0.showSwitchCameraButton = false
            $0.showCancelButton       = true
            $0.showOverlayView        = true
            $0.rectOfInterest         = CGRect(x: 0.2, y: 0.4, width: 0.6, height: 0.3)
        }
        
        return QRCodeReaderViewController(builder: builder)
    }()
    
    func decodeQRResult(result: String){
            debugLog(result)
            ApiController.downloadQuestByCode(code: result, completion: { (success, msg, downloadedQuest) in
                if(success){
                    self.selectedQuest = downloadedQuest
                    self.performSegue(withIdentifier: "taskSegue", sender: self)
                }else{
                    self.showErrorMessage(title: "Error", body: msg!, presentationStyle: .top, duration: 5.0, buttonTapHandler: nil, buttonTitle: nil, image: nil)
                }
            })
    }
}

extension StartViewController : QRCodeReaderViewControllerDelegate{
    
    @IBAction func scanAction(_ sender: AnyObject) {
        // Retrieve the QRCode content
        // By using the delegate pattern
        readerVC.delegate = self

        // Presents the readerVC as modal form sheet
        readerVC.modalPresentationStyle = .fullScreen

        present(readerVC, animated: true, completion: nil)
    }
    
    // MARK: - QRCodeReaderViewController Delegate Methods
    
    func reader(_ reader: QRCodeReaderViewController, didScanResult result: QRCodeReaderResult) {
        reader.stopScanning()

        dismiss(animated: true, completion: {
            self.decodeQRResult(result: result.value)
        })
    }
    
    //This is an optional delegate method, that allows you to be notified when the user switches the cameraName
    //By pressing on the switch camera button
    func reader(_ reader: QRCodeReaderViewController, didSwitchCamera newCaptureDevice: AVCaptureDeviceInput) {
        let cameraName = newCaptureDevice.device.localizedName
        debugLog("Switching capture to: \(cameraName)")
    }
    
    func readerDidCancel(_ reader: QRCodeReaderViewController) {
        reader.stopScanning()
        
        dismiss(animated: true, completion: nil)
    }
}
