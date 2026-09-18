//
//  TaskViewController.swift
//  Vault
//
//  Created by Carl Burnstein on 3/22/19.
//  Copyright © 2019 CASLS.
//

import UIKit
import AVFoundation
import AVKit
import ARKit
import SwiftMessages

class TaskViewController: UIViewController, UITextFieldDelegate {
    
    @IBOutlet weak var scrollView: UIScrollView!
    @IBOutlet weak var innerScrollView: UIView!
    @IBOutlet weak var titleLabel: UILabel!
    @IBOutlet weak var bodyTextView: UITextView!
    @IBOutlet weak var responseTextField: UITextField!
    @IBOutlet weak var completedView: UIView!
    @IBOutlet weak var correctResponseImageView: UIImageView!
    @IBOutlet weak var lockView: UIView!
    @IBOutlet weak var footerView: UIView!
    @IBOutlet weak var arStartView: UIView!
    @IBOutlet weak var bodyTextViewTopConstraint: NSLayoutConstraint!
    @IBOutlet weak var bodyTextViewHeightConstraint: NSLayoutConstraint!
    @IBOutlet var submitButton: DesignableButton!
    @IBOutlet var arButton: DesignableButton!
    @IBOutlet weak var bottomAnswer: UITextField!
    @IBOutlet weak var sitckyFooterView: UIView!
    @IBOutlet weak var arSession: DesignableButton!
    @IBOutlet weak var stickySubmit: DesignableButton!
    @IBOutlet weak var StickyBottom: UIView!
    
    
    public var task : Task!
    public var userTask : UserTask!
    public var localeTaskMeta : TaskMeta!
    public var arTargetTaskMetas : [TaskMeta] = []
    var audioPlayer: AVAudioPlayer?
    var speechRecognizerView: UIView?
    var speechRecognizerViewController: SpeechRecognitionViewController!
    let appDelegate = UIApplication.shared.delegate as! AppDelegate
    public var pagingViewController : CustomPagingViewController!
    public var taskPagingViewController : TaskPagingViewController!
    var lastMove = "none"

    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        
        // add a done button to keyboard
        let toolbar = UIToolbar()
        toolbar.sizeToFit()
        let flexableSpace = UIBarButtonItem(barButtonSystemItem: .flexibleSpace, target: nil, action: nil)
                
        let doneButton = UIBarButtonItem(barButtonSystemItem: .done, target: self, action: #selector(self.doneClicked))
                
        toolbar.setItems([flexableSpace, doneButton], animated: false)
        bottomAnswer.inputAccessoryView = toolbar
        
        //Set up the body text HTML
        let font = UIFont.systemFont(ofSize: 14)
        bodyTextView.attributedText = task.body.htmlAttributed(family: font.familyName, size: font.pointSize, color: UIColor(named:"color_label")!)
        
    }
    
    @objc func resetTask(sender: UIBarButtonItem) {
        let alertController = UIAlertController(title: "Wait!", message: "Are you sure you'd like to reset this task?", preferredStyle: .alert)
        alertController.addAction(UIAlertAction(title: "NO", style: .cancel, handler: nil))
        alertController.addAction(UIAlertAction(title: "YES", style: .destructive, handler: { _ in
            self.userTask.is_complete = false
            self.appDelegate.saveContext() //Save the UserTask object in CoreData
            ApiController.saveUserTaskToSyncQueue(userTask: self.userTask) //Add to the sync queue
            self.responseTextField.text = ""
            self.loadTask()
            self.pagingViewController.reloadMenu()
            self.pagingViewController.reloadData()
        }))
        present(alertController, animated: true, completion: nil)
    }
    
    func loadTask(){
        titleLabel.text = task.title
        responseTextField.delegate = self
        
        //Get the taskMetas array
        if let taskMetas = task.taskMetas{
            for tm in taskMetas{
                let taskMeta = tm as! TaskMeta
                //If there is a locale taskMeta, set it aside
                if(taskMeta.key == TaskMeta.key_locale){
                    localeTaskMeta = taskMeta
                }else if(taskMeta.key == TaskMeta.key_ar_target_id){
                    arTargetTaskMetas.append(taskMeta)
                }
            }
        }
        
        //Set up the speech recognition view controller
        if(task.task_type_id == Task.speechToText){
            let storyboard = UIStoryboard(name: "Main", bundle: nil)
            speechRecognizerViewController = (storyboard.instantiateViewController(withIdentifier: "SpeechRecognitionViewController") as! SpeechRecognitionViewController)
            speechRecognizerViewController.delegate = self
            if let taskMeta = localeTaskMeta{
                speechRecognizerViewController.localeTaskMeta = taskMeta
            }
            self.addChild(speechRecognizerViewController)
            speechRecognizerView = speechRecognizerViewController.view
            
            
            footerView.addSubview(speechRecognizerView!)
            footerView.constrainToEdges(speechRecognizerView!)
            
            sitckyFooterView.addSubview(speechRecognizerView!)
            sitckyFooterView.constrainToEdges(speechRecognizerView!)
            
        }else if(task.task_type_id == Task.arTarget){
            arButton.isHidden = false
            arSession.isHidden = false
            stickySubmit.isHidden = true
            bottomAnswer.isHidden = true
        }
        
        //Get the UserTask object
        if let ut = task.userTask{
            self.userTask = ut
            if(userTask.is_complete == true){
                correctResponseImageView.isHidden = false
                if let srvc = self.speechRecognizerViewController{
                    srvc.view.isHidden = false
                    srvc.correctResponseImageView.isHidden = false
                }
            }else{
                correctResponseImageView.isHidden = true
                if let srvc = self.speechRecognizerViewController{
                    srvc.correctResponseImageView.isHidden = true
                }
            }
        }

        correctResponseImageView.image = UIImage(named: "correct_circle")
        if let srvc = self.speechRecognizerViewController{
            srvc.correctResponseImageView.image = UIImage(named: "correct_circle")
        }
        
        if let media = task.getOrderedMedia(){
            var bodyTextTop : CGFloat = 16.0
            for med in media{
                if let type = med.type{
                    if (type == "image") {
                        if let local_path = med.getLocalPath(){
                            if(FileManager.default.fileExists(atPath: local_path)){
                                let image = UIImage(contentsOfFile: local_path)
                                let imageView = UIImageView(image: image)
                                imageView.contentMode = .scaleAspectFit
                                scrollView.addSubview(imageView)
                                imageView.translatesAutoresizingMaskIntoConstraints = false
                                scrollView.addConstraint(NSLayoutConstraint(item: imageView,
                                                                            attribute: .top,
                                                                            relatedBy: .equal,
                                                                            toItem: titleLabel,
                                                                            attribute: .bottom,
                                                                            multiplier: 1,
                                                                            constant: bodyTextTop
                                ))
                                scrollView.addConstraint(NSLayoutConstraint(item: imageView,
                                                                            attribute: .height,
                                                                            relatedBy: .lessThanOrEqual,
                                                                            toItem: nil,
                                                                            attribute: .notAnAttribute,
                                                                            multiplier: 1,
                                                                            constant: 243
                                ))
                                scrollView.addConstraint(NSLayoutConstraint(item: imageView,
                                                                            attribute: .leading,
                                                                            relatedBy: .equal,
                                                                            toItem: scrollView,
                                                                            attribute: .leading,
                                                                            multiplier: 1,
                                                                            constant: 16
                                ))
                                scrollView.addConstraint(NSLayoutConstraint(item: scrollView,
                                                                            attribute: .trailing,
                                                                            relatedBy: .equal,
                                                                            toItem: imageView,
                                                                            attribute: .trailing,
                                                                            multiplier: 1,
                                                                            constant: 16
                                ))
                                bodyTextTop += 243 + 32 //height of the image + 16 for padding.
                                bodyTextViewTopConstraint.constant = bodyTextTop
                            }
                        }
                    }else if (type == "audio"){
                        if let local_path = med.getLocalPath(){
                            let storyboard = UIStoryboard(name: "Main", bundle: nil)
                            let apvc = storyboard.instantiateViewController(withIdentifier: "AudioPlayerViewController") as! AudioPlayerViewController
                            apvc.filePath = local_path
                            self.addChild(apvc)
                            let playerView = apvc.view
                            scrollView.addSubview(playerView!)
                            playerView?.translatesAutoresizingMaskIntoConstraints = false
                            scrollView.addConstraint(NSLayoutConstraint(item: playerView!,
                                                                        attribute: .top,
                                                                        relatedBy: .equal,
                                                                        toItem: titleLabel,
                                                                        attribute: .bottom,
                                                                        multiplier: 1,
                                                                        constant: bodyTextTop
                            ))
                            scrollView.addConstraint(NSLayoutConstraint(item: playerView!,
                                                                        attribute: .height,
                                                                        relatedBy: .equal,
                                                                        toItem: nil,
                                                                        attribute: .notAnAttribute,
                                                                        multiplier: 1,
                                                                        constant: 60
                            ))
                            scrollView.addConstraint(NSLayoutConstraint(item: playerView!,
                                                                        attribute: .leading,
                                                                        relatedBy: .equal,
                                                                        toItem: scrollView,
                                                                        attribute: .leading,
                                                                        multiplier: 1,
                                                                        constant: 16
                            ))
                            scrollView.addConstraint(NSLayoutConstraint(item: scrollView,
                                                                        attribute: .trailing,
                                                                        relatedBy: .equal,
                                                                        toItem: playerView!,
                                                                        attribute: .trailing,
                                                                        multiplier: 1,
                                                                        constant: 16
                            ))
                            bodyTextTop += 60 + 32 //height of the image + 32 for padding.
                            bodyTextViewTopConstraint.constant = bodyTextTop
                        }
                    }else if(type == "video"){
                        if let local_path = med.getLocalPath(){
                            let player = AVPlayer(url: URL(fileURLWithPath: local_path))
                            
                            //Create the PlayerViewController to put as a subview
                            let playerController = AVPlayerViewController()
                            playerController.player = player
                            playerController.showsPlaybackControls = true
                            let playerView = playerController.view
                            let width = scrollView.frame.width - 32
                            playerView?.frame = CGRect(x: 0, y: 0, width: width, height: 243) //width scrollview - 32 for padding.
                            self.addChild(playerController)
                            scrollView.addSubview(playerController.view)
                            playerView?.translatesAutoresizingMaskIntoConstraints = false
                            scrollView.addConstraint(NSLayoutConstraint(item: playerView!,
                                                                        attribute: .top,
                                                                        relatedBy: .equal,
                                                                        toItem: titleLabel,
                                                                        attribute: .bottom,
                                                                        multiplier: 1,
                                                                        constant: bodyTextTop
                            ))
                            scrollView.addConstraint(NSLayoutConstraint(item: playerView!,
                                                                        attribute: .height,
                                                                        relatedBy: .lessThanOrEqual,
                                                                        toItem: nil,
                                                                        attribute: .notAnAttribute,
                                                                        multiplier: 1,
                                                                        constant: 243
                            ))
                            scrollView.addConstraint(NSLayoutConstraint(item: playerView!,
                                                                        attribute: .leading,
                                                                        relatedBy: .equal,
                                                                        toItem: scrollView,
                                                                        attribute: .leading,
                                                                        multiplier: 1,
                                                                        constant: 16
                            ))
                            scrollView.addConstraint(NSLayoutConstraint(item: scrollView,
                                                                        attribute: .trailing,
                                                                        relatedBy: .equal,
                                                                        toItem: playerView!,
                                                                        attribute: .trailing,
                                                                        multiplier: 1,
                                                                        constant: 16
                            ))
                            bodyTextTop += 243 + 32 //height of the image + 32 for padding.
                            bodyTextViewTopConstraint.constant = bodyTextTop
                        }
                    }
                }
            }
        }

        //Check if the task is locked by required tasks.
        if(task.isLocked() == true){
            lockView.isHidden = false
            self.view.bringSubviewToFront(lockView)
        }else{
            lockView.isHidden = true
            self.view.sendSubviewToBack(lockView)
        }
        
        // Hide the descriptive message if still on screen
        descriptiveMessage.hide()
    }
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        NotificationCenter.default.addObserver(self, selector: #selector(keyboardWillShow), name:UIResponder.keyboardWillShowNotification, object: nil)
        NotificationCenter.default.addObserver(self, selector: #selector(keyboardWillHide), name:UIResponder.keyboardWillHideNotification, object: nil)
        
        self.loadTask()

        self.setUpResetButton()
    }
    
    func setUpResetButton(){
        let menuBtn = UIButton(type: .custom)
        menuBtn.frame = CGRect(x: 0.0, y: 0.0, width: 30, height: 30)
        menuBtn.setImage(UIImage(named:"reset"), for: .normal)
        menuBtn.addTarget(self, action: #selector(resetTask), for: UIControl.Event.touchUpInside)
        
        let menuBarItem = UIBarButtonItem(customView: menuBtn)
        let currWidth = menuBarItem.customView?.widthAnchor.constraint(equalToConstant: 30)
        currWidth?.isActive = true
        let currHeight = menuBarItem.customView?.heightAnchor.constraint(equalToConstant: 30)
        currHeight?.isActive = true
        self.taskPagingViewController.navigationItem.rightBarButtonItem = menuBarItem
    }
    
    override func viewDidAppear(_ animated: Bool) {
        super.viewDidAppear(animated)
        sitckyFooterView.frame.origin.y = -6.0
        
        // Check for autocomplete after all set up completed
        if (task.auto_complete == 1)    {
            bottomAnswer.isHidden = true
            stickySubmit.isHidden = true
            debugLog(checkAnswer(userResponse: pagingViewController.AUTO_COMPLETE_RESPONSE, correctAnswer: pagingViewController.AUTO_COMPLETE_RESPONSE, hideMessages: true))
        }
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        NotificationCenter.default.removeObserver(self, name: UIResponder.keyboardWillShowNotification, object: nil)
        NotificationCenter.default.removeObserver(self, name: UIResponder.keyboardWillHideNotification, object: nil)
    }
    
    @objc func doneClicked(){
            view.endEditing(true)
        }
    
    @objc func keyboardWillShow(notification: NSNotification){
        guard let keyboardFrame = notification.userInfo![UIResponder.keyboardFrameEndUserInfoKey] as? NSValue else { return }
        
        let keyBoardHeight = view.convert(keyboardFrame.cgRectValue, from: nil)
        
        scrollView.contentInset.bottom = keyBoardHeight.size.height + 50
        scrollView.scrollIndicatorInsets = scrollView.contentInset
                
        scrollView.layer.zPosition = 10000 - 1
        StickyBottom.layer.zPosition = 10000
        
        if(lastMove != "up" || lastMove == "none" || lastMove == "down"){
            lastMove = "up"
            
            
            
            let padding = keyBoardHeight.size.height * 0.135
                        let keyboardFrame = keyBoardHeight.size.height - padding
                    
                        if self.view.frame.origin.y == 0{
                            
                            self.view.frame.origin.y -= keyboardFrame
                        }
            
            
            
    }
    }
    
    @objc func keyboardWillHide(notification: NSNotification){

        guard let keyboardFrame = notification.userInfo![UIResponder.keyboardFrameEndUserInfoKey] as? NSValue else { return }
        let keyBoardHeight = view.convert(keyboardFrame.cgRectValue, from: nil)
        
        scrollView.contentInset = .zero
        scrollView.scrollIndicatorInsets = scrollView.contentInset
        
        if(lastMove != "down" && lastMove != "none"){
            lastMove = "down"
            let padding = keyBoardHeight.size.height * 0.135
                        let keyboardFrame = keyBoardHeight.size.height - padding
                    
                        if self.view.frame.origin.y != 0{
                            
                            self.view.frame.origin.y
                                += keyboardFrame
                        }
        }
    }
    
    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        self.view.endEditing(true)
        return false
    }
    
    
    @IBAction func arSessionButtonTouched(_ sender: Any) {
        if(ARWorldTrackingConfiguration.isSupported){
            self.performSegue(withIdentifier: "arSessionSegue", sender: self)
        }else{
            self.showErrorMessage(title: "AR Not Supported", body: "Your device does not support augmented reality with ARKit. Please use a different device.", presentationStyle: .center, duration: 5, buttonTapHandler: nil, buttonTitle: nil, image: nil)
        }
    }
    
    @IBAction func submitButtonTouched(_ sender: Any) {
        doneClicked()
        responseTextField.resignFirstResponder()
        let correctAnswer = task.answer
        if let response = bottomAnswer.text{
            let isCorrect = checkAnswer(userResponse: response, correctAnswer: correctAnswer)
            debugLog(isCorrect)
        }
    }
    
    func checkAnswer(userResponse: String, correctAnswer: String, hideMessages: Bool = false) -> Bool{
        var responseText = ""
        //Hide any existing notifications
        SwiftMessages.hideAll()
        SwiftMessages.defaultConfig.ignoreDuplicates = false
        //lowercase both the response and correct answer so case can be ignored.
        if(userResponse.lowercased() == correctAnswer.lowercased()){
            responseText = "Correct!"
            if let correctResponseText = task.correct_response_text{
                if(correctResponseText != ""){
                    responseText = correctResponseText
                }
            }
            if let ut = self.userTask{
                ut.response_text = userResponse //Set the user's response to the response_text
                ut.is_complete = true
                self.appDelegate.saveContext() //Save the UserTask object in CoreData
                ApiController.saveUserTaskToSyncQueue(userTask: ut) //Add to the sync queue
                
                if(userTask.is_complete == true){
                    self.correctResponseImageView.isHidden = false
                    if let srvc = self.speechRecognizerViewController{
                        srvc.view.isHidden = false
                        srvc.correctResponseImageView.isHidden = false
                    }
                }else{
                    self.correctResponseImageView.isHidden = true
                    if let srvc = self.speechRecognizerViewController{
                        srvc.view.isHidden = true
                        srvc.correctResponseImageView.isHidden = true
                    }
                }
                self.pagingViewController.reloadMenu()
            }
            
             if let correctImage = task.correctResponseImageMedia {
                if let path = correctImage.getLocalPath(){
                    if let image = UIImage(contentsOfFile: path){
                        self.showSuccessMessage(title: "", body: responseText, presentationStyle: .center, duration: TimeInterval.infinity, buttonTapHandler: { (btn) in SwiftMessages.hide() }, buttonTitle: "Hide", image: image)
                        return true
                    }
                }
            }
            
            let lastTask = task.quest.getLastTask()
            let taskSortNum = task.sort_order
            
            if (!hideMessages)   {
                self.showSuccessMessage(title: "", body: responseText, presentationStyle: .center, duration: TimeInterval.infinity, buttonTapHandler: { (btn) in SwiftMessages.hide() }, buttonTitle: "Hide", image: nil)
                
                // To prevent overlap only show descriptive message when char count is 126 or fewer
                if(responseText.count < 126 && taskSortNum.isLess(than: lastTask)){
                self.showDescriptiveMessage(title: "", body: "Choose from any of the unlocked steps above to continue your progress.", presentationStyle: .center, duration: 5, buttonTapHandler: { (btn) in descriptiveMessage.hide(id: "showDescriptiveMessage") }, buttonTitle: "Hide", image: nil)
                }
            }
            
            //Correct response
            return true
        }else{
            responseText = "Try again!"
            if let incorrectResponseText = task.incorrect_response_text{
                if(incorrectResponseText != ""){
                    responseText = incorrectResponseText
                }
            }
            if let ut = self.userTask{
                ut.response_text = userResponse
                self.appDelegate.saveContext() //Save the UserTask object in CoreData
                ApiController.saveUserTaskToSyncQueue(userTask: ut) //Add to the sync queue
            }
            
            if let incorrectImage = task.incorrectResponseImageMedia {
                if let path = incorrectImage.getLocalPath(){
                    if let image = UIImage(contentsOfFile: path){
                         self.showErrorMessage(title: "", body: responseText, presentationStyle: .center, duration: TimeInterval.infinity, buttonTapHandler:  { (btn) in SwiftMessages.hide() }, buttonTitle: "Hide", image: image)
                        return true
                    }
                }
            }

            self.showErrorMessage(title: "", body: responseText, presentationStyle: .center, duration: TimeInterval.infinity, buttonTapHandler:  { (btn) in SwiftMessages.hide() }, buttonTitle: "Hide", image: nil)
            //Incorrect response
            return false
        }
    }
    
    
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
        if(segue.identifier == "arSessionSegue"){
            let arvc = segue.destination as! ARViewController
            arvc.pagingViewController = self.pagingViewController
            arvc.arTargetTaskMetas = self.arTargetTaskMetas
            arvc.task = self.task
            arvc.delegate = self
        }
    }
}

extension TaskViewController : SpeechRecognitionViewDelegate{
    func recognitionTaskCompleted(with result: String, sender: Any) {
        //check the result
        debugLog(result)
        let correctAnswer = task.answer
        let isCorrect = checkAnswer(userResponse: result, correctAnswer: correctAnswer)
        debugLog(isCorrect)
    }
}

//MARK: - ARViewControllerDelegate
extension TaskViewController : ARViewControllerDelegate{
    func didRecognizeImage() {
        var responseText = "Correct!"
        debugLog("Recognized Image")
        if let correctResponseText = task.correct_response_text{
            if(correctResponseText != ""){
                responseText = correctResponseText
            }
        }
        if let ut = self.userTask{
            ut.is_complete = true
            appDelegate.saveContext()
            ApiController.saveUserTaskToSyncQueue(userTask: ut)
            if(ut.is_complete == true){
                self.speechRecognizerView?.isHidden = true
            }else{
                self.speechRecognizerView?.isHidden = false
            }
        }
        
        DispatchQueue.main.sync {
            self.showSuccessMessage(title: "", body: responseText, presentationStyle: .top, duration: TimeInterval.infinity, buttonTapHandler:  { (btn) in SwiftMessages.hide() }, buttonTitle: "Hide", image: nil)
            self.pagingViewController.reloadMenu()
        }
    }
}
