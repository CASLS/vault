//
//  CustomSideMenuNavigationController.swift
//  Vault
//
//  Created by Carl Burnstein on 10/2/19.
//  Copyright © 2019 CASLS.
//

import UIKit
import SideMenu

class CustomSideMenuNavigationController: SideMenuNavigationController {

    override func awakeFromNib() {
        super.awakeFromNib()

        self.presentationStyle = .viewSlideOutMenuIn
        self.presentationStyle.onTopShadowOffset = CGSize(width: -3, height: 0)
        self.presentationStyle.onTopShadowColor = .black
        /// The radius of the shadow applied to the top most view.
        self.presentationStyle.onTopShadowRadius = 5
        /// The opacity of the shadow applied to the top most view.
        self.presentationStyle.onTopShadowOpacity = 0.5
        
        self.pushStyle = .default
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
    }

}

extension CustomSideMenuNavigationController: SideMenuNavigationControllerDelegate{
    func sideMenuWillAppear(menu: SideMenuNavigationController, animated: Bool) {
       }

       func sideMenuDidAppear(menu: SideMenuNavigationController, animated: Bool) {
       }

       func sideMenuWillDisappear(menu: SideMenuNavigationController, animated: Bool) {
       }

       func sideMenuDidDisappear(menu: SideMenuNavigationController, animated: Bool) {
       }
}


class CustomPresentionStyle: SideMenuPresentationStyle {

    required init() {
        super.init()
        /// Background color behind the views and status bar color
        backgroundColor = .black
        /// The starting alpha value of the menu before it appears
        menuStartAlpha = 1
        /// Whether or not the menu is on top. If false, the presenting view is on top. Shadows are applied to the view on top.
        menuOnTop = false
        /// The amount the menu is translated along the x-axis. Zero is stationary, negative values are off-screen, positive values are on screen.
        menuTranslateFactor = 0
        /// The amount the menu is scaled. Less than one shrinks the view, larger than one grows the view.
        menuScaleFactor = 1
        /// The color of the shadow applied to the top most view.
        onTopShadowColor = .black
        /// The radius of the shadow applied to the top most view.
        onTopShadowRadius = 5
        /// The opacity of the shadow applied to the top most view.
        onTopShadowOpacity = 0
        /// The offset of the shadow applied to the top most view.
        onTopShadowOffset = .zero
        /// The ending alpha of the presenting view when the menu is fully displayed.
        presentingEndAlpha = 1
        /// The amount the presenting view is translated along the x-axis. Zero is stationary, negative values are off-screen, positive values are on screen.
        presentingTranslateFactor = 0
        /// The amount the presenting view is scaled. Less than one shrinks the view, larger than one grows the view.
        presentingScaleFactor = 1
        /// The strength of the parallax effect on the presenting view once the menu is displayed.
        presentingParallaxStrength = .zero
    }
}
