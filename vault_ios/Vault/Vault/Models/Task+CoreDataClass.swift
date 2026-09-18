//
//  Task+CoreDataClass.swift
//  Vault
//
//  Created by Carl Burnstein on 3/20/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData
import ObjectMapper

@objc(Task)
public class Task: NSManagedObject, Mappable  {
    
    private var mediaArray : [Media]! = []
    private var taskMetasArray : [TaskMeta]! = []
    private var arTargetsArray : [ArTarget]! = []
    private var requiredTasksArray : [RequiredTask]! = []
    private var answerInt : Int!
    
    public static let text: Int16         = 1
    public static let arTarget: Int16     = 2
    public static let speechToText: Int16 = 3
    public static let webUrl: Int16 = 4
    public static let threeSixtyVideo: Int16 = 5
    public static let threeSixtyImage: Int16 = 6
    
    
    public required convenience init?(map: Map) {
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        let entity = NSEntityDescription.entity(forEntityName: "Task", in: ctx)
        self.init(entity: entity!, insertInto: nil) //nil makes it not save into context yet.
        mapping(map: map)
    }
    
    public func mapping(map: Map) {
        id                              <- map["id"]
        quest_id                    <- map["quest_id"]
        task_type_id                    <- map["task_type_id"]
        title                           <- map["title"]
        body                            <- map["body"]
        answer                          <- map["answer"]
        answerInt                       <- map["answer"]
        if(answerInt != nil){
            answer = String(answerInt)
        }
        auto_complete                   <- map["auto_complete"]
        is_active                       <- map["is_active"]
        sort_order                      <- map["sort_order"]
        correct_response_text           <- map["correct_response_text"]
        incorrect_response_text         <- map["incorrect_response_text"]
        correct_response_image_id       <- map["correct_response_image_id"]
        incorrect_response_image_id     <- map["incorrect_response_image_id"]
        created_at                      <- map["created_at"]
        updated_at                      <- map["updated_at"]
        userTask                        <- map["userTask"]
        mediaArray                      <- map["media"]
        media = NSSet(array: mediaArray)
        correctResponseImageMedia       <- map["correctResponseImage"]
        incorrectResponseImageMedia     <- map["incorrectResponseImage"]
        taskMetasArray                  <- map["taskMetas"]
        taskMetas = NSSet(array: taskMetasArray)
        arTargetsArray                  <- map["arTargets"]
        arTargets = NSSet(array: arTargetsArray)
        requiredTasksArray              <- map["requiredTasks"]
        requiredTasks = NSSet(array: requiredTasksArray)
    }
    
    public static func doesTaskExist(id: Int16) -> Bool{
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        let fetchRequest = NSFetchRequest<NSManagedObject>(entityName: "Task")
        fetchRequest.predicate = NSPredicate(format: "id = %d", id)
        
        var results: [NSManagedObject] = []
        
        do {
            results = try ctx.fetch(fetchRequest)
        }
        catch {
            debugLog("error executing fetch request: \(error)")
        }
        
        return results.count > 0
    }
    
    public func getOrderedMedia() -> [Media]!{
        if let media = self.media{
            let m = media.sortedArray(using: [NSSortDescriptor(key: "id", ascending: true)]) as! [Media]
            return m
        }else{
            return []
        }
    }
    
    //Get a single Task object by the ID.
    public static func get(id: Int16, context: NSManagedObjectContext?) -> Task?{
        var ctx : NSManagedObjectContext
        if(context == nil){
            ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        }else{
            ctx = context!
        }
        
        let fetchRequest = NSFetchRequest<NSManagedObject>(entityName: "Task")
        fetchRequest.predicate = NSPredicate(format: "id = %d", id)
        
        var results: [NSManagedObject] = []
        var task: Task? = nil
        do {
            results = try ctx.fetch(fetchRequest)
            if results.count > 0 {
                if let t = results.first as? Task{
                    task = t
                }
            }
        }
        catch {
            debugLog("error executing fetch request: \(error)")
        }
        
        
        return task
    }
    
    public func isLocked() -> Bool{
        var is_locked = false
        if let requiredTasks = self.requiredTasks{
            if(requiredTasks.count > 0){
                for rt in requiredTasks{
                    let requiredTask = rt as! RequiredTask
                    if let childTask = requiredTask.getChildTask(){
                        if let userTask = childTask.userTask{
                            if(userTask.is_complete == false){
                                is_locked = true
                            }
                        }else{
                            is_locked = true
                        }
                    }
                }
            }
        }
        
        return is_locked
    }
}
