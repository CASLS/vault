//
//  QuestsViewController.swift
//  Vault
//
//  Created by Carl Burnstein on 3/19/19.
//  Copyright © 2019 CASLS.
//

import UIKit
import SideMenu

private let reuseIdentifier = "QuestCell2"

class QuestsViewController: UIViewController, UICollectionViewDelegate, UICollectionViewDataSource, UICollectionViewDelegateFlowLayout {

    @IBOutlet weak var collectionView: UICollectionView!
    
    let appDelegate = UIApplication.shared.delegate as! AppDelegate
    var quests : [Quest] = []
    var selectedQuest : Quest!
    var user : User!
    var isFirstSync : Bool = true
    var showCompleted : Int16 = 0
    var showMyQuestsOnly : Int16 = 0
    
    private let refreshControl = UIRefreshControl()
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Add Refresh Control to Table View
        if #available(iOS 10.0, *) {
            collectionView.refreshControl = refreshControl
        } else {
            collectionView.addSubview(refreshControl)
        }
        // Configure Refresh Control
        refreshControl.addTarget(self, action: #selector(refreshQuestData(_:)), for: .valueChanged)
        let greenColor = UIColor.init(hexString: "#434444")
        refreshControl.tintColor = greenColor
        refreshControl.attributedTitle = NSAttributedString(string: "Fetching Latest Data ...", attributes: [NSAttributedString.Key.font: UIFont.systemFont(ofSize: 16), NSAttributedString.Key.foregroundColor: greenColor])

        let navigationController = self.navigationController
        navigationController?.navigationBar.tintColor = UIColor.white
        
        SideMenuManager.default.addPanGestureToPresent(toView: self.view)
        SideMenuManager.default.addPanGestureToPresent(toView: self.navigationController!.navigationBar)
    }
    
    override func viewDidAppear(_ animated: Bool) {
        super.viewDidAppear(animated)
        user = appDelegate.getUser()
        if(user == nil){
            performSegue(withIdentifier: "loginSegue", sender: self)
        }else{
                self.getQuests()
        }
    }

    public func getQuests(){
        ApiController.getQuests { (success, quests, errMsg) in
            if(self.showMyQuestsOnly == 1){
                //Get only quests that this user has a questUserAccess object for.
                if let quests = Quest.getMyQuests() {
                    self.quests = quests
                }
            }else{
                //Get all quests
                if let quests = Quest.getAll() {
                    self.quests = quests.filter( { return $0.is_complete == self.showCompleted } )
                }
            }
            
            self.collectionView.reloadData()
            self.refreshControl.endRefreshing()
        }
    }
    
    @objc private func refreshQuestData(_ sender: Any){
        ApiController.syncQueue()
        self.getQuests()
    }
    
    @IBAction func logoutButtonTouched(_ sender: Any) {
        performSegue(withIdentifier: "logoutUnwindSegue", sender: self)
    }
    
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
        if(segue.identifier == "taskSegue"){
            let tldvc = segue.destination as! TaskPagingViewController
            tldvc.quest = selectedQuest
        }else if(segue.identifier == "logoutSegue"){
            let lvc = segue.destination as! LoginViewController
            self.isFirstSync = true
            lvc.logout()
        }
    }
 
    
    // MARK: UICollectionViewDataSource
    
    func numberOfSections(in collectionView: UICollectionView) -> Int {
        // #warning Incomplete implementation, return the number of sections
        return 1
    }
    
    
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        // #warning Incomplete implementation, return the number of items
        return self.quests.count
    }
    
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        let cell = collectionView.dequeueReusableCell(withReuseIdentifier: reuseIdentifier, for: indexPath) as! QuestCell
        
        // Configure the cell
        let quest = quests[indexPath.row]
        cell.nameLabel.text = quest.name
        // added badge here
        cell.badge.image = UIImage(named: "incorrect")
        
        if let media = quest.media{
            if let local_path = media.getLocalPath(){
                if let image = UIImage(contentsOfFile: local_path){
                    cell.bgImageView.image = image
                    cell.bgImageView.contentMode = .scaleAspectFill
                }
            }
        }else{
            cell.bgImageView.image = nil
            cell.bgImageView.backgroundColor = UIColor(named: "color_cell_bg")

        }
        return cell
    }
    
    // MARK: UICollectionViewDelegate
    
    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {
        let quest = quests[indexPath.row]
        selectedQuest = quest
        
        //If we haven't downloaded any tasks yet, download them.
        if(selectedQuest.tasks!.count <= 0){
            ApiController.downloadQuest(quest_id: Int(selectedQuest!.id)) { (success, msg, downloadedQuest) in
                if(success){
                    self.selectedQuest = downloadedQuest
                    self.performSegue(withIdentifier: "taskSegue", sender: self)
                }else{
                    self.showErrorMessage(title: "Error", body: msg!, presentationStyle: .top, duration: 5.0, buttonTapHandler: nil, buttonTitle: nil, image: nil)
                }
            }
        }else{
            performSegue(withIdentifier: "taskSegue", sender: self)
        }
        
    }

    // MARK: UICollectionViewDelegateFlowLayout
    
    func collectionView(_: UICollectionView, layout: UICollectionViewLayout, sizeForItemAt: IndexPath) -> CGSize{
        let width = UIScreen.main.bounds.size.width / 1
        let height = CGFloat(150.0)
        return CGSize(width: width, height: height)
    }

    override func willRotate(to toInterfaceOrientation: UIInterfaceOrientation, duration: TimeInterval) {
        collectionView.collectionViewLayout.invalidateLayout()
        self.view.setNeedsDisplay()
    }
}
