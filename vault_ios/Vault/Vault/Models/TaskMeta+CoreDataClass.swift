//
//  TaskMeta+CoreDataClass.swift
//  Vault
//
//  Created by Carl Burnstein on 4/5/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData
import ObjectMapper

@objc(TaskMeta)
public class TaskMeta: NSManagedObject, Mappable  {
    
    public static let key_locale: String         = "locale"
    public static let key_ar_target_id: String   = "ar_target_id"
    public static let key_url: String            = "url"

    public required convenience init?(map: Map) {
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        let entity = NSEntityDescription.entity(forEntityName: "TaskMeta", in: ctx)
        self.init(entity: entity!, insertInto: nil) //nil makes it not save into context yet.
        mapping(map: map)
    }
    
    public func mapping(map: Map) {
        id              <- map["id"]
        task_id         <- map["task_id"]
        key             <- map["key"]
        value           <- map["value"]
        created_at      <- map["created_at"]
        updated_at      <- map["updated_at"]
        media           <- map["media"]
    }
}
