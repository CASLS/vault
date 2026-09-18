//
//  QuestUserAccess+CoreDataProperties.swift
//  Vault
//
//  Created by Carl Burnstein on 10/11/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData


extension QuestUserAccess {

    @nonobjc public class func fetchRequest() -> NSFetchRequest<QuestUserAccess> {
        return NSFetchRequest<QuestUserAccess>(entityName: "QuestUserAccess")
    }

    @NSManaged public var id: Int16
    @NSManaged public var user_id: Int16
    @NSManaged public var quest_id: Int16
    @NSManaged public var is_owner: Int16
    @NSManaged public var permission: Int16
    @NSManaged public var created_at: String?
    @NSManaged public var updated_at: String?
    @NSManaged public var quest: Quest?

}
