//
//  Oauth+CoreDataProperties.swift
//  Vault
//
//  Created by Carl Burnstein on 3/25/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData


extension Oauth {

    @nonobjc public class func fetchRequest() -> NSFetchRequest<Oauth> {
        return NSFetchRequest<Oauth>(entityName: "Oauth")
    }

    @NSManaged public var access_token: String
    @NSManaged public var created_at: String?
    @NSManaged public var expires_in: String?
    @NSManaged public var id: Int16
    @NSManaged public var source: String?
    @NSManaged public var state: String?
    @NSManaged public var token_type: String?
    @NSManaged public var uid: String?
    @NSManaged public var updated_at: String?
    @NSManaged public var user_id: Int16
    @NSManaged public var user: User?

}
