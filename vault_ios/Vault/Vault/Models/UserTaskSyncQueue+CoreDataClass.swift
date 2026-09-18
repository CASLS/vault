//
//  UserTaskSyncQueue+CoreDataClass.swift
//  Vault
//
//  Created by Carl Burnstein on 7/19/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData
import ObjectMapper

@objc(UserTaskSyncQueue)
public class UserTaskSyncQueue: NSManagedObject, Mappable {
    public required convenience init?(map: Map) {
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        let entity = NSEntityDescription.entity(forEntityName: "UserTaskSyncQueue", in: ctx)
        self.init(entity: entity!, insertInto: nil) //nil makes it not save into context yet.
        mapping(map: map)
    }
    
    public func mapping(map: Map) {
        id      <- map["id"]
        json    <- map["json"]
    }
    
    public static func getAll() -> [UserTaskSyncQueue]?{
        //Get all quest objects
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        let request = NSFetchRequest<UserTaskSyncQueue>(entityName: "UserTaskSyncQueue")
        do {
            let result = try ctx.fetch(request)
            return result
        } catch {
            debugLog("Failed")
            return nil
        }
    }
}
