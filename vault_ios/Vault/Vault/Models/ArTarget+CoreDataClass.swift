//
//  ArTarget+CoreDataClass.swift
//  Vault
//
//  Created by Carl Burnstein on 5/20/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData
import ObjectMapper

@objc(ArTarget)
public class ArTarget: NSManagedObject, Mappable {
    
    public var overlayImage : UIImage!
    
    public required convenience init?(map: Map) {
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        let entity = NSEntityDescription.entity(forEntityName: "ArTarget", in: ctx)
        self.init(entity: entity!, insertInto: nil) //nil makes it not save into context yet.
        mapping(map: map)
    }
    
    public func mapping(map: Map) {
        id                          <- map["id"]
        title                       <- map["title"]
        task_id                     <- map["task_id"]
        media_id                    <- map["media_id"]
        physical_width              <- map["physical_width"]
        overlay_media_id            <- map["overlay_media_id"]
        overlay_physical_width      <- map["overlay_physical_width"]
        audio_media_id              <- map["audio_media_id"]
        should_auto_close           <- map["should_auto_close"]
        close_after                 <- map["close_after"]
        created_at                  <- map["created_at"]
        updated_at                  <- map["updated_at"]
        media                       <- map["media"]
        overlayMedia                <- map["overlayMedia"]
        audioMedia                  <- map["audioMedia"]
        task                        <- map["task"]
    }
}
