//
//  Media+CoreDataClass.swift
//  Vault
//
//  Created by Carl Burnstein on 3/25/19.
//  Copyright © 2019 CASLS.
//
//

import Foundation
import CoreData
import ObjectMapper

@objc(Media)
public class Media: NSManagedObject, Mappable  {
    public required convenience init?(map: Map) {
        let ctx = (UIApplication.shared.delegate as! AppDelegate).persistentContainer.viewContext
        let entity = NSEntityDescription.entity(forEntityName: "Media", in: ctx)
        self.init(entity: entity!, insertInto: nil) //nil makes it not save into context yet.
        mapping(map: map)
    }
    
    public func mapping(map: Map) {
        id              <- map["id"]
        title           <- map["title"]
        uri             <- map["uri"]
        url             <- map["url"]
        type            <- map["type"]
        mime_type       <- map["mime_type"]
        file_size       <- map["file_size"]
        duration        <- map["duration"]
        created_at      <- map["created_at"]
        updated_at      <- map["updated_at"]
    }
    
    public override func willSave() {
        if(self.isDeleted){
            //Remove the media file on disk
            if let local_path = self.getLocalPath(){
                if(FileManager.default.fileExists(atPath: local_path)){
                    do {
                        try FileManager.default.removeItem(atPath: local_path)
                    } catch let error as NSError {
                        debugLog("Error: \(error.description)")
                    }
                }
            }
        }
    }
    
    public func getLocalPath() -> String!{
        if let title = self.title{
            let documentsURL = FileManager.default.urls(for: .documentDirectory, in: .userDomainMask)[0]
            let fileURL = documentsURL.appendingPathComponent(title)
            return fileURL.relativePath
        }else{
            return nil
        }
    }
    
    public func downloadData() -> Void{
        if let url = self.url{
            if let localPath = ApiController.downloadData(url: url){
                self.local_path = localPath //set the local file path into the core data entity
                let appDelegate = UIApplication.shared.delegate as! AppDelegate
                if Thread.isMainThread {
                    appDelegate.saveContext()
                } else {
                    DispatchQueue.main.async { appDelegate.saveContext() }
                }
            }else{
                debugLog("Failed downloading media")
                return
            }
        }
    }
}
