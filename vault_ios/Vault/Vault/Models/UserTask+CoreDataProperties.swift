//
//  UserTask+CoreDataProperties.swift
//  Vault
//
//  Created by Carl Burnstein on 3/25/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData


extension UserTask {

    @nonobjc public class func fetchRequest() -> NSFetchRequest<UserTask> {
        return NSFetchRequest<UserTask>(entityName: "UserTask")
    }

    @NSManaged public var id: Int16
    @NSManaged public var user_id: Int16
    @NSManaged public var task_id: Int16
    @NSManaged public var is_complete: Bool
    @NSManaged public var response_text: String?
    @NSManaged public var created_at: String?
    @NSManaged public var updated_at: String?
    @NSManaged public var user: User?
    @NSManaged public var task: Task?

}
