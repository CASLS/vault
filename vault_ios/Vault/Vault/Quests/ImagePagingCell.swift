import UIKit
import Parchment

class ImagePagingCell: PagingCell {
  
  fileprivate lazy var imageView: UIImageView = {
    let imageView = UIImageView(frame: .zero)
    return imageView
  }()
    
    fileprivate lazy var lockImageView: UIImageView = {
        let lockImageView = UIImageView(frame: .zero)
        lockImageView.contentMode = .scaleAspectFit
        lockImageView.backgroundColor = UIColor.lightGray
        lockImageView.isHidden = true
        return lockImageView
    }()
  
  fileprivate lazy var titleLabel: UILabel = {
    let label = UILabel(frame: .zero)
    if(UIDevice.current.userInterfaceIdiom == .pad){
        label.font = UIFont.systemFont(ofSize: 24, weight: UIFont.Weight.semibold)
    }else{
        label.font = UIFont.systemFont(ofSize: 14, weight: UIFont.Weight.semibold)
    }
    label.numberOfLines = 0
    return label
  }()
    
    fileprivate lazy var badgeImage: UIImageView = {
        let badgeImage = UIImageView(frame: .zero)
        badgeImage.contentMode = .topRight
        return badgeImage
    }()
  
  fileprivate lazy var paragraphStyle: NSParagraphStyle = {
    let paragraphStyle = NSMutableParagraphStyle()
    paragraphStyle.hyphenationFactor = 1
    paragraphStyle.alignment = .center
    return paragraphStyle
  }()
    
  override init(frame: CGRect) {
    super.init(frame: frame)
    contentView.layer.cornerRadius = 6
    lockImageView.layer.cornerRadius = 6
    contentView.addSubview(imageView)
    contentView.addSubview(lockImageView)
    contentView.addSubview(titleLabel)
    contentView.addSubview(badgeImage)
    
    contentView.constrainToEdges(imageView)
    contentView.constrainToEdges(lockImageView)
    contentView.constrainToEdges(titleLabel)
    contentView.badgeConstrainToEdges(badgeImage)
  }
  
  required init?(coder: NSCoder) {
    fatalError("init(coder:) has not been implemented")
  }
    
  let darkTeal = UIColor.init(hexString: "#008473")
  let midnightGreen = UIColor.init(hexString: "#1F4A53")
  
  override func setPagingItem(_ pagingItem: PagingItem, selected: Bool, options: PagingOptions) {

    let item = pagingItem as! TaskItem
    let unLockedBadge = UIImage(named: "unlocked_badge")
    let correctBadge = UIImage(named: "correct_badge")

    lockImageView.isHidden = false //always reset the lock view
    //If there is a task -> userTask and it is_complete
    if let task = item.task{
        if let userTask = task.userTask{
            if(userTask.is_complete == true){
                imageView.contentMode = .scaleAspectFit
            }
        }
        
        
        if(task.isLocked() == true){
            lockImageView.backgroundColor = UIColor.lightGray
            lockImageView.image = UIImage(named: "lock")
            titleLabel.isHidden = true
            badgeImage.isHidden = true
        }else{
            lockImageView.backgroundColor = midnightGreen
            lockImageView.layer.borderWidth = 2
            lockImageView.borderColor = darkTeal
            lockImageView.image = nil
            titleLabel.isHidden = false
            badgeImage.image = unLockedBadge
            badgeImage.isHidden = false
            
            if item.headerImage == UIImage(named: "20i_correct") {
                lockImageView.backgroundColor = darkTeal
                badgeImage.image = correctBadge
                badgeImage.isHidden = false
            }
        }
    }
    
    let titleText = NSAttributedString(
        string: item.title,
        attributes: [NSAttributedString.Key.paragraphStyle: paragraphStyle])
    
    titleLabel.attributedText = titleText
    titleLabel.textColor = UIColor.white
    
    if(item.index < 1){
        lockImageView.backgroundColor = UIColor.lightGray
        titleLabel.textColor = UIColor.black
        lockImageView.image = nil
        titleLabel.isHidden = false
        badgeImage.isHidden = true
    }
    
    imageView.transform = CGAffineTransform(scaleX: 2, y: 2)
  }
}
 
