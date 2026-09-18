//
//  Task+CoreDataProperties.swift
//  Vault
//
//  Created by Carl Burnstein on 6/12/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData


extension Task {

    @nonobjc public class func fetchRequest() -> NSFetchRequest<Task> {
        return NSFetchRequest<Task>(entityName: "Task")
    }

    @NSManaged public var answer: String
    @NSManaged public var body: String
    @NSManaged public var correct_response_image_id: Int16
    @NSManaged public var correct_response_text: String?
    @NSManaged public var created_at: String?
    @NSManaged public var id: Int16
    @NSManaged public var incorrect_response_image_id: Int16
    @NSManaged public var incorrect_response_text: String?
    @NSManaged public var quest_id: Int16
    @NSManaged public var task_type_id: Int16
    @NSManaged public var title: String
    @NSManaged public var updated_at: String?
    @NSManaged public var auto_complete: Int16
    @NSManaged public var is_active: Int16
    @NSManaged public var sort_order: Float
    @NSManaged public var correctResponseImageMedia: Media?
    @NSManaged public var incorrectResponseImageMedia: Media?
    @NSManaged public var media: NSSet?
    @NSManaged public var quest: Quest
    @NSManaged public var taskMetas: NSSet?
    @NSManaged public var userTask: UserTask?
    @NSManaged public var userTasks: NSSet?
    @NSManaged public var arTargets: NSSet?
    @NSManaged public var requiredTasks: NSSet?

}

// MARK: Generated accessors for media
extension Task {

    @objc(addMediaObject:)
    @NSManaged public func addToMedia(_ value: Media)

    @objc(removeMediaObject:)
    @NSManaged public func removeFromMedia(_ value: Media)

    @objc(addMedia:)
    @NSManaged public func addToMedia(_ values: NSSet)

    @objc(removeMedia:)
    @NSManaged public func removeFromMedia(_ values: NSSet)

}

// MARK: Generated accessors for taskMetas
extension Task {

    @objc(addTaskMetasObject:)
    @NSManaged public func addToTaskMetas(_ value: TaskMeta)

    @objc(removeTaskMetasObject:)
    @NSManaged public func removeFromTaskMetas(_ value: TaskMeta)

    @objc(addTaskMetas:)
    @NSManaged public func addToTaskMetas(_ values: NSSet)

    @objc(removeTaskMetas:)
    @NSManaged public func removeFromTaskMetas(_ values: NSSet)

}

// MARK: Generated accessors for userTasks
extension Task {

    @objc(addUserTasksObject:)
    @NSManaged public func addToUserTasks(_ value: UserTask)

    @objc(removeUserTasksObject:)
    @NSManaged public func removeFromUserTasks(_ value: UserTask)

    @objc(addUserTasks:)
    @NSManaged public func addToUserTasks(_ values: NSSet)

    @objc(removeUserTasks:)
    @NSManaged public func removeFromUserTasks(_ values: NSSet)

}

// MARK: Generated accessors for arTargets
extension Task {

    @objc(addArTargetsObject:)
    @NSManaged public func addToArTargets(_ value: ArTarget)

    @objc(removeArTargetsObject:)
    @NSManaged public func removeFromArTargets(_ value: ArTarget)

    @objc(addArTargets:)
    @NSManaged public func addToArTargets(_ values: NSSet)

    @objc(removeArTargets:)
    @NSManaged public func removeFromArTargets(_ values: NSSet)

}

// MARK: Generated accessors for requiredTasks
extension Task {
    
    @objc(addRequiredTasksObject:)
    @NSManaged public func addToRequiredTasks(_ value: RequiredTask)
    
    @objc(removeRequiredTasksObject:)
    @NSManaged public func removeFromRequiredTasks(_ value: RequiredTask)
    
    @objc(addRequiredTasks:)
    @NSManaged public func addToRequiredTasks(_ values: NSSet)
    
    @objc(removeRequiredTasks:)
    @NSManaged public func removeFromRequiredTasks(_ values: NSSet)
    
}
