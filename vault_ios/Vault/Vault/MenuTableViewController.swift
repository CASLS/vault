//
//  MenuTableViewController.swift
//  Vault
//
//  Created by Carl Burnstein on 9/30/19.
//  Copyright © 2019 CASLS.
//

import UIKit
import SideMenu

class MenuTableViewController: UIViewController {
    
    @IBOutlet weak var accountLabel: UILabel!
    @IBOutlet var tableView: UITableView!
    
    var user : User!
    let appDelegate = UIApplication.shared.delegate as! AppDelegate
    
    override func viewDidLoad() {
        super.viewDidLoad()
    }
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        
        user = appDelegate.getUser()
        if(user != nil){
            let accountSource = user.oauth?.source ?? "";
            if (accountSource == "guest")   {
                accountLabel.text = "Guest"
            }   else    {
                accountLabel.text = user.email
            }
        }
        
        tableView.reloadData()
    }

    // MARK: - Navigation
    
    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
        if(segue.identifier == "logoutSegue"){
            let lvc = segue.destination as! LoginViewController
            accountLabel.text = ""
            lvc.logout()
        }else if(segue.identifier == "currentSegue"){
            let qvc = segue.destination as! QuestsViewController
            qvc.showCompleted = 0
            qvc.showMyQuestsOnly = 0
            qvc.navigationItem.title = "Current"
        }else if(segue.identifier == "completedSegue"){
            let qvc = segue.destination as! QuestsViewController
            qvc.showCompleted = 1
            qvc.showMyQuestsOnly = 0
            qvc.navigationItem.title = "Completed"
        }else if(segue.identifier == "myQuestsSegue"){
            let qvc = segue.destination as! QuestsViewController
            qvc.showCompleted = 0
            qvc.showMyQuestsOnly = 1
            qvc.navigationItem.title = "My Quests"
        }
    }
    
    
}

extension MenuTableViewController: UITableViewDelegate{
    
}

// MARK: - Table view data source
extension MenuTableViewController: UITableViewDataSource{
    func numberOfSections(in tableView: UITableView) -> Int {
        
        return 1
    }
    
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        if(section == 0){
            if(user != nil && user.user_type_id == User.TYPE_USER ){
                return 1
            }else{
                return 4
            }
        }
        
        return 0
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell : UITableViewCell!
        if(indexPath.row == 0){
            cell = tableView.dequeueReusableCell(withIdentifier: "startCell", for: indexPath)
        }else if(indexPath.row == 1){
            cell = tableView.dequeueReusableCell(withIdentifier: "currentCell", for: indexPath)
        }else if(indexPath.row == 2){
            cell = tableView.dequeueReusableCell(withIdentifier: "completedCell", for: indexPath)
        }else if(indexPath.row == 3){
            cell = tableView.dequeueReusableCell(withIdentifier: "myQuestsCell", for: indexPath)
        }else{
            cell = tableView.dequeueReusableCell(withIdentifier: "startCell", for: indexPath)
        }
        
        // Configure the cell...
        
        return cell
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        if(indexPath.row == 0){
            self.performSegue(withIdentifier: "startSegue", sender: self)
        }else if(indexPath.row == 1){
            self.performSegue(withIdentifier: "currentSegue", sender: self)
        }else if(indexPath.row == 2){
            self.performSegue(withIdentifier: "completedSegue", sender: self)
        }else if(indexPath.row == 3){
            self.performSegue(withIdentifier: "myQuestsSegue", sender: self)
        }
    }
}
