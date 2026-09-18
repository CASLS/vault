//
//  RequiredTask+CoreDataProperties.swift
//  Vault
//
//  Created by Carl Burnstein on 7/30/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData


extension RequiredTask {

    @nonobjc public class func fetchRequest() -> NSFetchRequest<RequiredTask> {
        return NSFetchRequest<RequiredTask>(entityName: "RequiredTask")
    }

    @NSManaged public var child_task_id: Int16
    @NSManaged public var created_at: String?
    @NSManaged public var id: Int16
    @NSManaged public var parent_task_id: Int16
    @NSManaged public var childTask: Task?
    @NSManaged public var parentTask: Task

}
