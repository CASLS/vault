//
//  UserTask+CoreDataClass.swift
//  Vault
//
//  Created by Carl Burnstein on 3/25/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData
import ObjectMapper

@objc(UserTask)
public class UserTask: NSManagedObject, Mappable   {
    public required convenience init?(map: Map) {
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        let entity = NSEntityDescription.entity(forEntityName: "UserTask", in: ctx)
        self.init(entity: entity!, insertInto: nil) //nil makes it not save into context yet.
        mapping(map: map)
    }
    
    public func mapping(map: Map) {
        map.shouldIncludeNilValues = true
        id              <- map["id"]
        user_id         <- map["user_id"]
        task_id         <- map["task_id"]
        is_complete     <- map["is_complete"]
        response_text   <- map["response_text"]
        created_at      <- map["created_at"]
        updated_at      <- map["updated_at"]
    }
    
    //Get a single UserTask object by the ID.
    public static func get(id: Int16, context: NSManagedObjectContext?) -> UserTask?{
        var ctx : NSManagedObjectContext
        if(context == nil){
            ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        }else{
            ctx = context!
        }
        
        let fetchRequest = NSFetchRequest<NSManagedObject>(entityName: "UserTask")
        fetchRequest.predicate = NSPredicate(format: "id = %d", id)
        
        var results: [NSManagedObject] = []
        var userTask: UserTask? = nil
        do {
            results = try ctx.fetch(fetchRequest)
            if results.count > 0 {
                if let tl = results.first as? UserTask{
                    userTask = tl
                }
            }
        }
        catch {
            debugLog("error executing fetch request: \(error)")
        }
        
        
        return userTask
    }
}
