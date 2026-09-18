//
//  Media+CoreDataProperties.swift
//  Vault
//
//  Created by Carl Burnstein on 3/26/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData


extension Media {

    @nonobjc public class func fetchRequest() -> NSFetchRequest<Media> {
        return NSFetchRequest<Media>(entityName: "Media")
    }

    @NSManaged public var id: Int16
    @NSManaged public var title: String?
    @NSManaged public var uri: String?
    @NSManaged public var url: String?
    @NSManaged public var type: String?
    @NSManaged public var mime_type: String?
    @NSManaged public var file_size: Int32
    @NSManaged public var duration: Double
    @NSManaged public var created_at: String?
    @NSManaged public var updated_at: String?
    @NSManaged public var data: NSData?
    @NSManaged public var local_path: String?
    @NSManaged public var tasks: NSSet?

}

// MARK: Generated accessors for tasks
extension Media {

    @objc(addTasksObject:)
    @NSManaged public func addToTasks(_ value: Task)

    @objc(removeTasksObject:)
    @NSManaged public func removeFromTasks(_ value: Task)

    @objc(addTasks:)
    @NSManaged public func addToTasks(_ values: NSSet)

    @objc(removeTasks:)
    @NSManaged public func removeFromTasks(_ values: NSSet)

}
