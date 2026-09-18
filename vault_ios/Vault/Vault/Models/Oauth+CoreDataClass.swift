//
//  Oauth+CoreDataClass.swift
//  Vault
//
//  Created by Carl Burnstein on 3/15/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData
import ObjectMapper

@objc(Oauth)
public class Oauth: NSManagedObject, Mappable {
    public required convenience init?(map: Map) {
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        let entity = NSEntityDescription.entity(forEntityName: "Oauth", in: ctx)
        self.init(entity: entity!, insertInto: ctx)
        
        mapping(map: map)
    }
    
    public func mapping(map: Map) {
        id              <- map["id"]
        user_id         <- map["user_id"]
        access_token    <- map["access_token"]
        token_type      <- map["token_type"]
        uid             <- map["uid"]
        expires_in      <- map["expires_in"]
        source          <- map["source"]
        state           <- map["state"]
        created_at      <- map["created_at"]
        updated_at      <- map["updated_at"]
    }
}
