//
//  User+CoreDataClass.swift
//  Vault
//
//  Created by Carl Burnstein on 3/15/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData
import ObjectMapper

@objc(User)
public class User: NSManagedObject, Mappable {
    
    public static let TYPE_SUPER_ADMIN : Int16 = 1
    public static let TYPE_ADMIN       : Int16 = 2
    public static let TYPE_USER        : Int16 = 3
    public static let TYPE_EDITOR      : Int16 = 4
    
    public required convenience init?(map: Map) {
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        let entity = NSEntityDescription.entity(forEntityName: "User", in: ctx)
        self.init(entity: entity!, insertInto: ctx)
        
        mapping(map: map)
    }
    
    public func mapping(map: Map) {
        id                      <- map["id"]
        user_type_id            <- map["user_type_id"]
        username                <- map["username"]
        email                   <- map["email"]
        auth_key                <- map["auth_key"]
        password_reset_token    <- map["password_reset_token"]
        status                  <- map["status"]
        created_at              <- map["created_at"]
        updated_at              <- map["updated_at"]
    }
    
    
}
