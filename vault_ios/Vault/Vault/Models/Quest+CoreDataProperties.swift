//
//  Quest+CoreDataProperties.swift
//  Vault
//
//  Created by Carl Burnstein on 10/11/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData


extension Quest {

    @nonobjc public class func fetchRequest() -> NSFetchRequest<Quest> {
        return NSFetchRequest<Quest>(entityName: "Quest")
    }

    @NSManaged public var created_at: String?
    @NSManaged public var desc: String?
    @NSManaged public var id: Int16
    @NSManaged public var is_active: Int16
    @NSManaged public var is_complete: Int16
    @NSManaged public var media_id: Int16
    @NSManaged public var name: String
    @NSManaged public var paging_image_id: Int16
    @NSManaged public var updated_at: String?
    @NSManaged public var code: String
    @NSManaged public var media: Media?
    @NSManaged public var pagingImage: Media?
    @NSManaged public var tasks: NSSet?
    @NSManaged public var questUserAccesses: NSSet?

}

// MARK: Generated accessors for tasks
extension Quest {

    @objc(addTasksObject:)
    @NSManaged public func addToTasks(_ value: Task)

    @objc(removeTasksObject:)
    @NSManaged public func removeFromTasks(_ value: Task)

    @objc(addTasks:)
    @NSManaged public func addToTasks(_ values: NSSet)

    @objc(removeTasks:)
    @NSManaged public func removeFromTasks(_ values: NSSet)

}

// MARK: Generated accessors for questUserAccesses
extension Quest {

    @objc(addQuestUserAccessesObject:)
    @NSManaged public func addToQuestUserAccesses(_ value: QuestUserAccess)

    @objc(removeQuestUserAccessesObject:)
    @NSManaged public func removeFromQuestUserAccesses(_ value: QuestUserAccess)

    @objc(addQuestUserAccesses:)
    @NSManaged public func addToQuestUserAccesses(_ values: NSSet)

    @objc(removeQuestUserAccesses:)
    @NSManaged public func removeFromQuestUserAccesses(_ values: NSSet)

}
