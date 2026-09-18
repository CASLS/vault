//
//  User+CoreDataProperties.swift
//  Vault
//
//  Created by Carl Burnstein on 3/25/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData


extension User {

    @nonobjc public class func fetchRequest() -> NSFetchRequest<User> {
        return NSFetchRequest<User>(entityName: "User")
    }

    @NSManaged public var auth_key: String?
    @NSManaged public var created_at: String?
    @NSManaged public var email: String
    @NSManaged public var id: Int16
    @NSManaged public var password_reset_token: String?
    @NSManaged public var status: Int16
    @NSManaged public var updated_at: String?
    @NSManaged public var user_type_id: Int16
    @NSManaged public var username: String
    @NSManaged public var oauth: Oauth?
    @NSManaged public var userTasks: NSSet?

}

// MARK: Generated accessors for userTasks
extension User {

    @objc(addUserTasksObject:)
    @NSManaged public func addToUserTasks(_ value: UserTask)

    @objc(removeUserTasksObject:)
    @NSManaged public func removeFromUserTasks(_ value: UserTask)

    @objc(addUserTasks:)
    @NSManaged public func addToUserTasks(_ values: NSSet)

    @objc(removeUserTasks:)
    @NSManaged public func removeFromUserTasks(_ values: NSSet)

}
