//
//  WebObjectViewController.swift
//  Vault
//
//  Created by Carl Burnstein on 10/13/19.
//  Copyright © 2019 CASLS.
//

import UIKit
import WebKit

class WebObjectViewController: UIViewController {

    @IBOutlet weak var webView: WKWebView!
    @IBOutlet weak var lockView: UIView!
    
    var url = ""
    public var task : Task!
    let appDelegate = UIApplication.shared.delegate as! AppDelegate
    public var pagingViewController : CustomPagingViewController!
    public var taskIndex : Int!
    
    override func viewDidLoad() {
        super.viewDidLoad()

        webView.navigationDelegate = self
    }
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        
        //Check if the task is locked by required tasks.
       if(task.isLocked() == true){
           lockView.isHidden = false
           self.view.bringSubviewToFront(lockView)
       }else{
           lockView.isHidden = true
           self.view.sendSubviewToBack(lockView)
       }
    }
    
    override func viewDidAppear(_ animated: Bool) {
        super.viewDidAppear(animated)
        
        // Do any additional setup after loading the view.
        if(url != ""){
            if let u = URL(string: url){
                let request = URLRequest(url: u)
                DispatchQueue.main.async {
                    self.webView.load(request)
                    ApiController.startActivityIndicator()
                }
            }
        }
        
        // Only check auto complete if task is not locked
        if (task.auto_complete == 1 && !task.isLocked())    {
            if let ut = task.userTask{
                if (!ut.is_complete)    {
                    ut.response_text = pagingViewController.AUTO_COMPLETE_RESPONSE
                    ut.is_complete = true
                    self.appDelegate.saveContext() //Save the UserTask object in CoreData
                    ApiController.saveUserTaskToSyncQueue(userTask: ut) //Add to the sync queue

                    self.pagingViewController.reloadMenu()
                }
            }
        }
    }
    

    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
    }
    */

}

extension WebObjectViewController : WKNavigationDelegate{
    func webView(_ webView: WKWebView, didFail navigation: WKNavigation!, withError error: Error) {
        debugLog(error.localizedDescription)
        ApiController.stopActivityIndicator()
    }
    
    func webView(_ webView: WKWebView, didFailProvisionalNavigation navigation: WKNavigation!, withError error: Error) {
        debugLog(error.localizedDescription)
        ApiController.stopActivityIndicator()
    }
    
    func webView(_ webView: WKWebView, didFinish navigation: WKNavigation!) {
        ApiController.stopActivityIndicator()
    }
}
