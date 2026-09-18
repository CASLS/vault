//
//  Quest+CoreDataClass.swift
//  Vault
//
//  Created by Carl Burnstein on 3/19/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData
import ObjectMapper

@objc(Quest)
public class Quest: NSManagedObject, Mappable  {
    
    private var tasksArray : [Task]! = []
    private var questUserAccessArray : [QuestUserAccess]! = []
    public required convenience init?(map: Map) {
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        let entity = NSEntityDescription.entity(forEntityName: "Quest", in: ctx)
        self.init(entity: entity!, insertInto: nil) //nil makes it not save into context yet.
        mapping(map: map)
    }
    
    public func update(JSONString: String){
        if let jsonQuest = Mapper<Quest>().map(JSONString: JSONString){
            self.id = jsonQuest.id
            self.name = jsonQuest.name
            self.is_active = jsonQuest.is_active
            self.is_complete = jsonQuest.is_complete
            self.desc = jsonQuest.desc
            if(self.media_id != jsonQuest.media_id || self.media == nil){
                //Download the quest media.
                if let questMedia = jsonQuest.media{
                    let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
                    ctx.insert(questMedia)
                    self.media = questMedia
                    questMedia.downloadData()
                }
            }
            
            if(self.paging_image_id != jsonQuest.paging_image_id || self.pagingImage == nil){
                //Download the quest media.
                if let pagingImage = jsonQuest.pagingImage{
                    let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
                    ctx.insert(pagingImage)
                    self.pagingImage = pagingImage
                    pagingImage.downloadData()
                }
            }
            
            //Delete existing questUserAccess objects
            if let questUserAccesses = self.questUserAccesses{
                for qua in questUserAccesses{
                    let questUSerAccess = qua as! QuestUserAccess
                    let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
                    ctx.delete(questUSerAccess)
                }
            }
            
            //Get the questUserAccess entites and insert into context
            if let questUserAccesses = jsonQuest.questUserAccesses{
                for qua in questUserAccesses as NSSet{
                    let questUserAccess = qua as! QuestUserAccess
                    let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
                    ctx.insert(questUserAccess)
                    questUserAccess.quest = self
                }
            }
            
            self.paging_image_id = jsonQuest.paging_image_id
            self.media_id = jsonQuest.media_id
            self.code = jsonQuest.code
            self.created_at = jsonQuest.created_at
            self.updated_at = jsonQuest.updated_at
        }
    }
    
    public func mapping(map: Map) {
        id                  <- map["id"]
        name                <- map["name"]
        is_active           <- map["is_active"]
        is_complete         <- map["is_complete"]
        desc                <- map["description"]
        created_at          <- map["created_at"]
        updated_at          <- map["updated_at"]
        tasksArray          <- map["tasks"]
        tasks = NSSet(array: tasksArray)
        media_id            <- map["media_id"]
        media               <- map["media"]
        paging_image_id     <- map["paging_image_id"]
        pagingImage         <- map["pagingImage"]
        code                <- map["code"]
        questUserAccessArray <- map["questUserAccesses"]
        questUserAccesses = NSSet(array: questUserAccessArray)
    }
    
    public static func doesQuestExist(id: Int16) -> Bool{
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        let fetchRequest = NSFetchRequest<NSManagedObject>(entityName: "Quest")
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
    
    public static func getAll() -> [Quest]?{
        //Get all quest objects
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        let request = NSFetchRequest<Quest>(entityName: "Quest")
        request.predicate = NSPredicate(format: "is_active == 1")
        do {
            let result = try ctx.fetch(request)
            return result
        } catch {
            debugLog("Failed")
            return nil
        }
    }
    
    public static func getMyQuests() -> [Quest]?{
        //Get all quest objects
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        let request = NSFetchRequest<Quest>(entityName: "Quest")
        var finalArray : [Quest] = []
        do {
            let results = try ctx.fetch(request)
            for result in results{
                if(result.questUserAccesses!.count > 0){
                    finalArray.append(result)
                }
            }
            
            return finalArray
        } catch {
            debugLog("Failed")
            return nil
        }
    }
    
    //Geet a single Quest object by the ID.
    public static func get(id: Int16, context: NSManagedObjectContext?) -> Quest?{
        var ctx : NSManagedObjectContext
        if(context == nil){
            ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        }else{
            ctx = context!
        }
        
        let fetchRequest = NSFetchRequest<NSManagedObject>(entityName: "Quest")
        fetchRequest.predicate = NSPredicate(format: "id = %d", id)
        
        var results: [NSManagedObject] = []
        var quest: Quest? = nil
        do {
            results = try ctx.fetch(fetchRequest)
            if results.count > 0 {
                if let tl = results.first as? Quest{
                    quest = tl
                }
            }
        }
        catch {
            debugLog("error executing fetch request: \(error)")
        }
        
        
        return quest
    }
    
    public func getOrderedTasks() -> [Task]?{
        var orderedTasks : [Task] = []
        if let tasks = self.tasks{
            for t in tasks{
                let task = t as! Task
                orderedTasks.append(task)
            }
            
            return orderedTasks.sorted { $0.sort_order < $1.sort_order }
        }
        
        return orderedTasks
    }
    
    public func getLastTask() -> Float{
        var orderedTasks : [Task] = []
        var maxFloat: Float = 0.00
        if let tasks = self.tasks{
            for t in tasks{
                let task = t as! Task
                orderedTasks.append(task)
                let sortFloat = task.sort_order
                
                if( maxFloat.isLess(than: sortFloat)){
                    maxFloat = sortFloat
                }

            }
            return maxFloat
        }
        return maxFloat
    }
    
    public func getCompletedTasks() -> [Task]?{
        var completedTasks : [Task] = []
        if let tasks = self.getOrderedTasks(){
            for task in tasks{
                if let userTask = task.userTask{
                    if(userTask.is_complete == true){
                        completedTasks.append(task)
                    }
                }
            }
            
            return completedTasks.sorted { $0.id < $1.id }
        }
        
        return completedTasks
    }
}
