//
//  ArTarget+CoreDataProperties.swift
//  Vault
//
//  Created by Carl Burnstein on 5/20/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData


extension ArTarget {

    @nonobjc public class func fetchRequest() -> NSFetchRequest<ArTarget> {
        return NSFetchRequest<ArTarget>(entityName: "ArTarget")
    }

    @NSManaged public var id: Int16
    @NSManaged public var title: String?
    @NSManaged public var task_id: Int16
    @NSManaged public var media_id: Int16
    @NSManaged public var physical_width: Float
    @NSManaged public var overlay_media_id: Int16
    @NSManaged public var audio_media_id: Int16
    @NSManaged public var overlay_physical_width: Float
    @NSManaged public var should_auto_close: Int16
    @NSManaged public var close_after: Int16
    @NSManaged public var created_at: String?
    @NSManaged public var updated_at: String?
    @NSManaged public var media: Media?
    @NSManaged public var overlayMedia: Media?
    @NSManaged public var task: Task?
    @NSManaged public var audioMedia: Media?

}
