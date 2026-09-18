//
//  TaskPagingViewController.swift
//  Vault
//
//  Created by Carl Burnstein on 3/22/19.
//  Copyright © 2019 CASLS.
//

import UIKit
import Parchment
import SwiftMessages

// First thing we need to do is create our own PagingItem that will
// hold the data for the different menu items. The header image is the
// image that will be displayed in the menu and the title will be
// overlayed above that.  We also need to store the array of images
// that we want to show when the item is tapped.

struct TaskItem: PagingItem, Hashable, Comparable{
    let index: Int
    let taskId: Int16
    let title: String
    let headerImage: UIImage
    let sortOrder: Float
    let task : Task!
    
    static func ==(lhs: TaskItem, rhs: TaskItem) -> Bool {
        return lhs.taskId == rhs.taskId && lhs.title == rhs.title
    }
    
    static func <(lhs: TaskItem, rhs: TaskItem) -> Bool {
        return lhs.sortOrder < rhs.sortOrder
    }
}

// Create our own custom paging view and override the layout
// constraints. The default implementation constrains the menu view
// to the page menu, but we want to keep them independent and store
// the height constraint so that we can update it later.
class CustomPagingView: PagingView {
    
    var menuHeightConstraint: NSLayoutConstraint?
    
    override func setupConstraints() {
        pageView.translatesAutoresizingMaskIntoConstraints = false
        collectionView.translatesAutoresizingMaskIntoConstraints = false
        
        menuHeightConstraint = collectionView.heightAnchor.constraint(equalToConstant: options.menuHeight)
        menuHeightConstraint?.isActive = true
        
        NSLayoutConstraint.activate([
            collectionView.leadingAnchor.constraint(equalTo: leadingAnchor),
            collectionView.trailingAnchor.constraint(equalTo: trailingAnchor),
            collectionView.topAnchor.constraint(equalTo: topAnchor),
            
            pageView.leadingAnchor.constraint(equalTo: leadingAnchor),
            pageView.trailingAnchor.constraint(equalTo: trailingAnchor),
            pageView.bottomAnchor.constraint(equalTo: bottomAnchor),
            pageView.topAnchor.constraint(equalTo: collectionView.bottomAnchor)
        ])
    }
}

// Create a custom paging view controller and override the view with
// our own custom subclass.
class CustomPagingViewController: PagingViewController {
    public let AUTO_COMPLETE_RESPONSE : String = "task auto completed"
    
    override func loadView() {
        view = CustomPagingView(
            options: options,
            collectionView: collectionView,
            pageView: pageViewController.view
        )
    }
    
    override func reloadMenu() {
        let tpvc = self.parent as! TaskPagingViewController
        tpvc.refreshTaskItems()
        super.reloadMenu()
    }
}

class TaskPagingViewController: UIViewController {
    
    var quest : Quest!
    var tasks : [Task] = []
    
    private var items : [TaskItem] = []
    
    // Create our custom paging view controller.
    private let pagingViewController = CustomPagingViewController()
    
    // Store the menu insets and item size and use that to calculate
    // the height of the collection view. We will use these values later
    // to calculate the height of the menu based on the scroll.
    private let menuInsets = UIEdgeInsets(top: 12, left: 18, bottom: 12, right: 18)
    private var menuItemSize = CGSize(width: 80, height: 60)
    
    private var menuHeight: CGFloat {
        return menuItemSize.height + menuInsets.top + menuInsets.bottom
    }
    
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        if(UIDevice.current.userInterfaceIdiom == .pad){
            menuItemSize = CGSize(width: 140, height: 100)
        }
        
        refreshTaskItems()

        pagingViewController.register(ImagePagingCell.self, for: TaskItem.self)
        pagingViewController.menuItemSize = .fixed(width: menuItemSize.width, height: menuItemSize.height)
        pagingViewController.menuItemSpacing = 8
        pagingViewController.menuInsets = menuInsets
        pagingViewController.borderColor = UIColor(white: 0, alpha: 0.1)

        if #available(iOS 13.0, *) {
            pagingViewController.backgroundColor = UIColor(named: "color_view_bg")!
            pagingViewController.menuBackgroundColor = UIColor(named: "color_view_bg")!
            pagingViewController.indicatorColor = .secondaryLabel
        }else{
            pagingViewController.indicatorColor = UIColor(named: "color_view_bg")!
        }
        
        pagingViewController.indicatorOptions = .visible(
            height: 2,
            zIndex: Int.max,
            spacing: UIEdgeInsets.zero,
            insets: UIEdgeInsets.zero
        )
        
        pagingViewController.borderOptions = .visible(
            height: 1,
            zIndex: Int.max - 1,
            insets: UIEdgeInsets(top: 0, left: 18, bottom: 0, right: 18)
        )
        
        // Add the paging view controller as a child view controller and
        // contrain it to all edges.
        addChild(pagingViewController)
        view.addSubview(pagingViewController.view)
        view.constrainToEdges(pagingViewController.view)
        pagingViewController.didMove(toParent: self)
        

        updateMenu(height: menuHeight)
        
        // Set our data source and delegate.
        pagingViewController.dataSource = self
        pagingViewController.delegate = self
        
        // Set the first item as the selected paging item.
        pagingViewController.select(pagingItem: items[0])
        
        // prevent swipe back to start page when in a quest
        navigationController?.interactivePopGestureRecognizer?.isEnabled = false
    }
    
    override func viewDidDisappear(_ animated: Bool) {
        SwiftMessages.hideAll()
    }
    
    func refreshTaskItems(){
        //Reset the array
        self.items.removeAll()
        self.tasks.removeAll()
        //Create the Quest item
        var pagingIcon : UIImage!
        var launchPagingIcon : UIImage!

        
        launchPagingIcon = UIImage(named: "20i_launch")!
        if let pagingImage = quest.pagingImage{
            pagingIcon = UIImage(contentsOfFile: pagingImage.getLocalPath())
        }else{
            pagingIcon = UIImage(named: "20i_unlocked")!
        }
        let questItem = TaskItem(index: 0, taskId: 0, title: quest.name, headerImage: launchPagingIcon, sortOrder: 0.0, task: nil)
        
        self.items.append(questItem)
        
        //Get the tasks ordered by IDs
        if let tasks = quest.getOrderedTasks(){
            var i = 1 //Starting at 1 cuz index 0 is the QuestItem
            for t in tasks{
                let task = t
                self.tasks.append(task)
                var taskItem : TaskItem!
                if let userTask = task.userTask{
                    
                    
                    if(userTask.is_complete == true){
                            if let image = UIImage(named: "20i_correct"){
                                taskItem = TaskItem(index: i, taskId: task.id, title: task.title, headerImage: image, sortOrder: task.sort_order, task: task)
                            }
                    }
                }
                if(taskItem == nil){
                    taskItem = TaskItem(index: i, taskId: task.id, title: task.title, headerImage: pagingIcon, sortOrder: task.sort_order, task: task)
                }
                self.items.append(taskItem)
                i = i + 1
            }
        }
        
        self.items = self.items.sorted()
    }
    
    private func calculateMenuHeight(for scrollView: UIScrollView) -> CGFloat {
        // Calculate the height of the menu view based on the scroll view
        // content offset.
        let maxChange: CGFloat = 50
        let offset = min(maxChange, scrollView.contentOffset.y + menuHeight) / maxChange
        let height = menuHeight - (offset * maxChange)
        return height
    }
    
    private func updateMenu(height: CGFloat) {
        guard let menuView = pagingViewController.view as? CustomPagingView else { return }
        
        // Update the height constraint of the menu view.
        menuView.menuHeightConstraint?.constant = height
        
        // Update the size of the menu items.
        pagingViewController.menuItemSize = .fixed(
            width: menuItemSize.width,
            height: height - menuInsets.top - menuInsets.bottom
        )
        
        // Invalidate the collection view layout and call layoutIfNeeded
        // to make sure the collection is updated.
        pagingViewController.collectionViewLayout.invalidateLayout()
        pagingViewController.collectionView.layoutIfNeeded()
    }
    
}

extension TaskPagingViewController: PagingViewControllerDataSource {
    
    func pagingViewController(_ pagingViewController: PagingViewController, viewControllerAt index: Int) -> UIViewController {
        //If index 0, then it's the QuestViewController
        if(index == 0){
            let storyboard = UIStoryboard(name: "Main", bundle: nil)
            let viewController = storyboard.instantiateViewController(withIdentifier: "QuestDetailsViewController") as! QuestDetailsViewController
            viewController.quest = self.quest
            viewController.pagingViewController = self.pagingViewController
            viewController.taskPagingViewController = self;
            return viewController
        }else{
            let task = tasks[index-1] // -1 because of the questDetailsViewController being the first item.
            let storyboard = UIStoryboard(name: "Main", bundle: nil)
            // Load each of the view controllers you want to embed
            // from the storyboard.
            if(task.task_type_id == Task.webUrl){
                let viewController = storyboard.instantiateViewController(withIdentifier: "WebObjectViewController") as! WebObjectViewController
                var url : String!
                if let taskMetas = task.taskMetas{
                    for tm in taskMetas{
                        let taskMeta = tm as! TaskMeta
                        if(taskMeta.key == TaskMeta.key_url){
                            url = taskMeta.value
                        }
                    }
                }
                viewController.url = url
                viewController.task = task
                viewController.pagingViewController = self.pagingViewController
                viewController.taskIndex = index
                return viewController
            }else if(task.task_type_id == Task.threeSixtyVideo){
                let viewController = storyboard.instantiateViewController(withIdentifier: "ThreeSixtyVideoViewController") as! ThreeSixtyVideoViewController
                viewController.task = task
                viewController.pagingViewController = self.pagingViewController
                return viewController
            }else if(task.task_type_id == Task.threeSixtyImage){
                let viewController = storyboard.instantiateViewController(withIdentifier: "ThreeSixtyImageViewController") as! ThreeSixtyImageViewController
                viewController.task = task
                return viewController
            }else{
                let viewController = storyboard.instantiateViewController(withIdentifier: "TaskViewController") as! TaskViewController
                viewController.task = task
                viewController.pagingViewController = self.pagingViewController
                viewController.taskPagingViewController = self;
                return viewController
            }
            
            
            
        }
    }
    
    func pagingViewController(_ pagingViewController: PagingViewController, pagingItemAt index: Int) -> PagingItem {
        return items[index]
    }
    
    func numberOfViewControllers(in: PagingViewController) -> Int{
        return items.count
    }
    
}

extension TaskPagingViewController: PagingViewControllerDelegate {
    //When a page did scroll to a new page.
    func pagingViewController(
            _ pagingViewController: PagingViewController,
            didScrollToItem pagingItem: PagingItem,
            startingViewController: UIViewController?,
            destinationViewController: UIViewController,
            transitionSuccessful: Bool) {
        
        if(transitionSuccessful){
            ApiController.syncQueue() //Sync the queue if possible
        }
                
    }
    func pagingViewController(
            _: PagingViewController,
            isScrollingFromItem currentPagingItem: PagingItem,
            toItem upcomingPagingItem: PagingItem?,
            startingViewController: UIViewController,
            destinationViewController: UIViewController?,
            progress: CGFloat) {
        updateMenu(height: menuHeight)
    }
    
}
