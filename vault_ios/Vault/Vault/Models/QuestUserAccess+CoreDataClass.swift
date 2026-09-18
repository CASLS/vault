//
//  QuestUserAccess+CoreDataClass.swift
//  Vault
//
//  Created by Carl Burnstein on 10/11/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData
import ObjectMapper

@objc(QuestUserAccess)
public class QuestUserAccess: NSManagedObject, Mappable   {
    
    static let PERMISSION_VIEW = 0
    static let PERMISSION_EDIT = 1

    public required convenience init?(map: Map) {
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        let entity = NSEntityDescription.entity(forEntityName: "QuestUserAccess", in: ctx)
        self.init(entity: entity!, insertInto: nil) //nil makes it not save into context yet.
        mapping(map: map)
    }

    public func mapping(map: Map) {
        id              <- map["id"]
        user_id         <- map["user_id"]
        quest_id        <- map["quest_id"]
        is_owner        <- map["is_owner"]
        permission      <- map["permission"]
        updated_at      <- map["updated_at"]
        created_at      <- map["created_at"]
    }

}
