//
//  RequiredTask+CoreDataClass.swift
//  Vault
//
//  Created by Carl Burnstein on 7/30/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData
import ObjectMapper

@objc(RequiredTask)
public class RequiredTask: NSManagedObject, Mappable   {
    
    public required convenience init?(map: Map) {
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        let entity = NSEntityDescription.entity(forEntityName: "RequiredTask", in: ctx)
        self.init(entity: entity!, insertInto: nil) //nil makes it not save into context yet.
        mapping(map: map)
    }
    
    public func mapping(map: Map) {
        id              <- map["id"]
        parent_task_id  <- map["parent_task_id"]
        child_task_id   <- map["child_task_id"]
        created_at      <- map["created_at"]
    }

    public func getChildTask() -> Task!{
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        if let childTask = Task.get(id: self.child_task_id, context: ctx){
            return childTask
        }
        
        return nil
    }
}
