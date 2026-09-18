//
//  QuestDetailsViewController.swift
//  Vault
//
//  Created by Carl Burnstein on 3/21/19.
//  Copyright © 2019 CASLS.
//

import UIKit

class QuestDetailsViewController: UIViewController {
    @IBOutlet weak var nameLabel: UILabel!
    @IBOutlet weak var badge: UIImageView!
    @IBOutlet weak var descriptionTextView: UITextView!
    @IBOutlet weak var taskCountLabel: UILabel!
    
    var quest : Quest!
    
    public var pagingViewController : CustomPagingViewController!
    public var taskPagingViewController : TaskPagingViewController!
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        nameLabel.text = quest.name

        if let description = quest.desc{
            let font = UIFont.systemFont(ofSize: 14)
            descriptionTextView.attributedText = description.htmlAttributed(family: font.familyName, size: font.pointSize, color: UIColor(named:"color_label")!)
        }
        
        self.updateCompletedCount()
    }
    
    func updateCompletedCount(){
        let completedTasks = quest.getCompletedTasks()!.filter({ return $0.task_type_id != Task.webUrl})
        let totalTasks = quest.tasks?.filter({ return ($0 as! Task).task_type_id != Task.webUrl})
        taskCountLabel.text = "Tasks completed: \(completedTasks.count)/\(totalTasks?.count ?? 0)"
    }
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        self.setUpDownloadButton()
        self.updateCompletedCount()
    }
    
    func setUpDownloadButton(){
        let menuBtn = UIButton(type: .custom)
        menuBtn.frame = CGRect(x: 0.0, y: 0.0, width: 30, height: 30)
        menuBtn.setImage(UIImage(named:"download"), for: .normal)
        menuBtn.addTarget(self, action: #selector(downloadQuest), for: UIControl.Event.touchUpInside)
        
        let menuBarItem = UIBarButtonItem(customView: menuBtn)
        let currWidth = menuBarItem.customView?.widthAnchor.constraint(equalToConstant: 30)
        currWidth?.isActive = true
        let currHeight = menuBarItem.customView?.heightAnchor.constraint(equalToConstant: 30)
        currHeight?.isActive = true
        self.taskPagingViewController.navigationItem.rightBarButtonItem = menuBarItem
    }
    
    @objc func downloadQuest(sender: UIBarButtonItem) {
        let alertController = UIAlertController(title: "Wait!", message: "Are you sure you'd like to download this quest? This will download all of the latest data and media for this quest.", preferredStyle: .alert)
        alertController.addAction(UIAlertAction(title: "NO", style: .cancel, handler: nil))
        alertController.addAction(UIAlertAction(title: "YES", style: .destructive, handler: { _ in
            ApiController.downloadQuest(quest_id: Int(self.quest!.id)) { (success, message, quest) in
                if(success){
                    self.quest = quest
                    self.taskPagingViewController.quest = quest
                    self.taskPagingViewController.refreshTaskItems()
                    self.pagingViewController.reloadData()
                    self.pagingViewController.reloadMenu()
                }else{
                    self.showErrorMessage(title: "Error", body: message!, presentationStyle: .top, duration: 4.0, buttonTapHandler: nil, buttonTitle: nil, image: nil)
                }
            }
        }))
        present(alertController, animated: true, completion: nil)
    }
    
    @IBAction func startButtonTouched(_ sender: Any) {
        pagingViewController.select(index: 1, animated: true) //Go to the next view controller in the pagingViewController
    }
    
    @IBAction func resetButtonTouched(_ sender: Any) {
        let alertController = UIAlertController(title: "Wait!", message: "Are you sure you'd like to reset this quest?", preferredStyle: .alert)
        alertController.addAction(UIAlertAction(title: "NO", style: .cancel, handler: nil))
        alertController.addAction(UIAlertAction(title: "YES", style: .destructive, handler: { _ in
            ApiController.resetQuest(quest_id: Int(self.quest!.id)) { (success, message, quest) in
                if(success){
                    debugLog(quest!)
                    self.taskPagingViewController.quest = quest
                    self.taskPagingViewController.refreshTaskItems()
                    self.pagingViewController.reloadData()
                }else{
                    self.showErrorMessage(title: "Error", body: message!, presentationStyle: .top, duration: 5.0, buttonTapHandler: nil, buttonTitle: nil, image: nil )
                }
            }
        }))
        present(alertController, animated: true, completion: nil)
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
