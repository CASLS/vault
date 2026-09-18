//
//  UIView+SwiftMessages.swift
//  Vault
//
//  Created by Carl Burnstein on 6/20/19.
//  Copyright © 2019 CASLS.
//

import Foundation
import SwiftMessages
let descriptiveMessage = SwiftMessages()
let midnightGreen = UIColor(hexString: "1F4A53")

extension UIViewController {
    func showErrorMessage(title: String?, body: String, presentationStyle: SwiftMessages.PresentationStyle, duration: TimeInterval?, buttonTapHandler: ((UIButton) -> Void)?, buttonTitle: String?, image: UIImage?){
        var config = SwiftMessages.defaultConfig
        config.presentationStyle = presentationStyle
        config.duration = .seconds(seconds: duration!)
        config.interactiveHide = false
        
        let darkRed = UIColor(hexString: "B05145")
        
        let alertView : MessageView!
        if let incorrectImage = image{
            alertView =  try! SwiftMessages.viewFromNib(named: "CustomCenteredView")
            alertView.configureTheme(.error, iconStyle: .default)
            alertView.iconImageView?.image = incorrectImage
            alertView.iconImageView?.contentMode = .scaleAspectFit
        }else{
            alertView = MessageView.viewFromNib(layout: .cardView)
            alertView.configureTheme(.error, iconStyle: .none)
        }
       
        alertView.button?.isHidden = true
        alertView.configureDropShadow()
        alertView.backgroundView.backgroundColor = darkRed
        
        if let title = title{
            alertView.configureContent(title: title, body: body)
            
        }else{
            alertView.configureContent(body: body)
        }
        
        if let buttonTapHandler = buttonTapHandler{
            alertView.buttonTapHandler = buttonTapHandler
            alertView.button?.isHidden = false
            alertView.button?.tintColor = darkRed
            if let buttonTitle = buttonTitle{
                alertView.button?.setTitle(buttonTitle, for: .normal)
                alertView.button?.tintColor = darkRed
            }else{
                alertView.button?.setTitle("OK", for: .normal)
                alertView.button?.tintColor = darkRed
            }
        }else{
            alertView.button?.isHidden = true
            config.interactiveHide = true
        }
        SwiftMessages.show(config: config, view: alertView)
    }
    
    func showMessage(title: String?, body: String, presentationStyle: SwiftMessages.PresentationStyle, duration: TimeInterval?, buttonTapHandler: ((UIButton) -> Void)?, buttonTitle: String?, image: UIImage?){
        var config = SwiftMessages.defaultConfig
        config.presentationStyle = presentationStyle
        config.duration = .seconds(seconds: duration!)
        config.interactiveHide = false
        
        let alertView : MessageView!
        if let img = image{
            alertView =  try! SwiftMessages.viewFromNib(named: "CustomCenteredView")
            alertView.configureTheme(.info, iconStyle: .default)
            alertView.iconImageView?.contentMode = .scaleAspectFit
            alertView.iconImageView?.image = img
        }else{
            alertView = MessageView.viewFromNib(layout: .cardView)
            alertView.configureTheme(.info, iconStyle: .subtle)
        }
        
        alertView.configureDropShadow()
        alertView.backgroundView.backgroundColor = midnightGreen
        
        if let title = title{
            alertView.configureContent(title: title, body: body)
        }else{
            alertView.configureContent(body: body)
        }
        
        if let buttonTapHandler = buttonTapHandler{
            alertView.buttonTapHandler = buttonTapHandler
            alertView.button?.isHidden = false
            if let buttonTitle = buttonTitle{
                alertView.button?.setTitle(buttonTitle, for: .normal)
                alertView.button?.tintColor = midnightGreen
            }else{
                alertView.button?.setTitle("OK", for: .normal)
                alertView.button?.tintColor = midnightGreen
            }
        }else{
            alertView.button?.isHidden = true
            config.interactiveHide = true
        }
        SwiftMessages.show(config: config, view: alertView)
    }
    
    func showSuccessMessage(title: String?, body: String, presentationStyle: SwiftMessages.PresentationStyle, duration: TimeInterval?, buttonTapHandler: ((UIButton) -> Void)?, buttonTitle: String?, image: UIImage?){
        var config = SwiftMessages.defaultConfig
        config.presentationStyle = presentationStyle
        config.duration = .seconds(seconds: duration!)
        config.interactiveHide = false
        
        let darkTeal = UIColor(hexString: "008375")
        
        let alertView : MessageView!
        if let correctImage = image{
            alertView =  try! SwiftMessages.viewFromNib(named: "CustomCenteredView")
            alertView.configureTheme(.success, iconStyle: .default)
            alertView.iconImageView?.contentMode = .scaleAspectFit
            alertView.iconImageView?.image = correctImage
        }else{
            alertView = MessageView.viewFromNib(layout: .cardView)
            alertView.configureTheme(.success, iconStyle: .subtle)
        }

        alertView.button?.isHidden = true
        alertView.backgroundView.backgroundColor = darkTeal
        alertView.configureDropShadow()
        alertView.id = "showSuccessMessage"
        
        if let title = title{
            alertView.configureContent(title: title, body: body)
        }else{
            alertView.configureContent(body: body)
        }
        
        if let buttonTapHandler = buttonTapHandler{
            alertView.buttonTapHandler = buttonTapHandler
            alertView.button?.isHidden = false
            if let buttonTitle = buttonTitle{
                alertView.button?.setTitle(buttonTitle, for: .normal)
                alertView.button?.tintColor = darkTeal
            }else{
                alertView.button?.setTitle("OK", for: .normal)
                alertView.button?.tintColor = darkTeal
            }
        }else{
            alertView.button?.isHidden = true
            config.interactiveHide = true
        }
        
        SwiftMessages.show(config: config, view: alertView)
    }
    
 
func showDescriptiveMessage(title: String?, body: String, presentationStyle: SwiftMessages.PresentationStyle, duration: TimeInterval?, buttonTapHandler: ((UIButton) -> Void)?, buttonTitle: String?, image: UIImage?){
    var config = SwiftMessages.defaultConfig
    config.presentationStyle = presentationStyle
    config.duration = .seconds(seconds: duration!)
    config.interactiveHide = false
    
    let alertView : MessageView!
    alertView = MessageView.viewFromNib(layout: .cardView)
    alertView.configureTheme(.success, iconStyle: .none)
    alertView.backgroundView.backgroundColor = midnightGreen
    alertView.configureDropShadow()
    alertView.topLayoutMarginAddition = 220
    alertView.id = "showDescriptiveMessage"
    
    if let title = title{
        alertView.configureContent(title: title, body: body)
    }else{
        alertView.configureContent(body: body)
    }
    
    if let buttonTapHandler = buttonTapHandler{
        alertView.buttonTapHandler = buttonTapHandler
        alertView.button?.isHidden = false
        
        if let buttonTitle = buttonTitle{
            alertView.button?.setTitle(buttonTitle, for: .normal)
            alertView.button?.tintColor = midnightGreen
            alertView.button?.isHidden = true
        }else{
            alertView.button?.setTitle("OK", for: .normal)
            alertView.button?.tintColor = midnightGreen
            alertView.button?.isHidden = true
        }
    }else{
        alertView.button?.isHidden = true
        config.interactiveHide = true
    }
    
    descriptiveMessage.show(config: config, view: alertView)
  }

}
