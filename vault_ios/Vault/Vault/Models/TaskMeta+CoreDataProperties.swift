//
//  TaskMeta+CoreDataProperties.swift
//  Vault
//
//  Created by Carl Burnstein on 4/5/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData


extension TaskMeta {

    @nonobjc public class func fetchRequest() -> NSFetchRequest<TaskMeta> {
        return NSFetchRequest<TaskMeta>(entityName: "TaskMeta")
    }

    @NSManaged public var id: Int16
    @NSManaged public var task_id: Int16
    @NSManaged public var key: String
    @NSManaged public var value: String
    @NSManaged public var created_at: String?
    @NSManaged public var updated_at: String?
    @NSManaged public var task: Task
    @NSManaged public var media: Media?

}
