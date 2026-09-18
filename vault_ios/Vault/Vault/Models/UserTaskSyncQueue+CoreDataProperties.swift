//
//  UserTaskSyncQueue+CoreDataProperties.swift
//  Vault
//
//  Created by Carl Burnstein on 7/19/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData


extension UserTaskSyncQueue {

    @nonobjc public class func fetchRequest() -> NSFetchRequest<UserTaskSyncQueue> {
        return NSFetchRequest<UserTaskSyncQueue>(entityName: "UserTaskSyncQueue")
    }

    @NSManaged public var id: Int16
    @NSManaged public var json: String?

}
