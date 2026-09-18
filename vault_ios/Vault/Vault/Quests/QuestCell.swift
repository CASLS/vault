//
//  QuestCell.swift
//  Vault
//
//  Created by Carl Burnstein on 3/19/19.
//  Copyright © 2019 CASLS.
//

import UIKit

@IBDesignable
class QuestCell: UICollectionViewCell {
    @IBOutlet weak var nameLabel: UILabel!
    @IBOutlet weak var badge: UIImageView!
    @IBOutlet weak var bgView: DesignableView!
    @IBOutlet weak var bgImageView: UIImageView!
    // added badge here
    
    override var isHighlighted: Bool{
        didSet{
            if isHighlighted{
                UIView.animate(withDuration: 0.2, delay: 0.0, usingSpringWithDamping: 0.8, initialSpringVelocity: 1.0, options: .curveEaseOut, animations: {
                    self.transform = self.transform.scaledBy(x: 0.90, y: 0.90)
                }, completion: nil)
            }else{
                UIView.animate(withDuration: 0.2, delay: 0.0, usingSpringWithDamping: 0.4, initialSpringVelocity: 1.0, options: .curveEaseOut, animations: {
                    self.transform = CGAffineTransform.identity.scaledBy(x: 1.0, y: 1.0)
                }, completion: nil)
            }
        }
    }
}
