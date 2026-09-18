/*
See LICENSE folder for this sample’s licensing information.

Abstract:
Main view controller for the AR experience.
*/

import ARKit
import SceneKit
import UIKit

protocol ARViewControllerDelegate: AnyObject{
    func didRecognizeImage()
}

class ARViewController: UIViewController, ARSCNViewDelegate {
    
    @IBOutlet var sceneView: ARSCNView!
    
    @IBOutlet weak var blurView: UIVisualEffectView!
    
    public var pagingViewController : CustomPagingViewController!
    public var arTargetTaskMetas : [TaskMeta] = []
    public var task : Task?
    var arTargets : [ArTarget] = []
    var recognizedTarget : ArTarget!
    var detectionImages : [ARReferenceImage] = []
    var overlayImages : [UIImage] = []
    var audioFilePath : String!
    var audioPlayer: AVAudioPlayer!
    weak var activityIndicator: UIActivityIndicatorView?
    var isLoading = true
    var hasLoaded = false
    weak var delegate: ARViewControllerDelegate?
    
    /// The view controller that displays the status and "restart experience" UI.
    lazy var statusViewController: ARStatusViewController = {
        return children.lazy.compactMap({ $0 as? ARStatusViewController }).first!
    }()
    
    /// A serial queue for thread safety when modifying the SceneKit node graph.
    let updateQueue = DispatchQueue(label: Bundle.main.bundleIdentifier! +
        ".serialSceneKitQueue")
    
    /// Convenience accessor for the session owned by ARSCNView.
    var session: ARSession {
        return sceneView.session
    }
    
    // MARK: - View Controller Life Cycle
    
    override func viewDidLoad() {
        super.viewDidLoad()
        sceneView.delegate = self
        sceneView.session.delegate = self
        
        let activityIndicator = UIActivityIndicatorView(style: .whiteLarge)
        activityIndicator.startAnimating()
        activityIndicator.translatesAutoresizingMaskIntoConstraints = false
        
        view.addSubview(activityIndicator)
        NSLayoutConstraint.activate([view.centerXAnchor.constraint(equalTo: activityIndicator.centerXAnchor, constant: 0),
                                     view.centerYAnchor.constraint(equalTo: activityIndicator.centerYAnchor, constant: 0)])
        self.activityIndicator = activityIndicator
        
        //Unqrap the ar target objcets
        if let arTargets = task?.arTargets{
            for at in arTargets{
                let arTarget = at as! ArTarget
                self.arTargets.append(arTarget)
                //Get the media from the arTarget object and create the ARReferenceImage
                if let media = arTarget.media{
                    debugLog(media)
                    if let type = media.type{
                        if(type == "image"){
                            if let local_path = media.getLocalPath(){
                                if(FileManager.default.fileExists(atPath: local_path)){
                                    if let image = UIImage(contentsOfFile: local_path){
                                        if let cgImage = image.cgImage{
                                            let arImage = ARReferenceImage(cgImage, orientation: .up, physicalWidth: CGFloat(arTarget.physical_width))
                                            if let title = arTarget.title{
                                                arImage.name = title
                                            }else{
                                                if let title = media.title{
                                                    arImage.name = title
                                                }
                                            }
                                            detectionImages.append(arImage)
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
                if let overlayMedia = arTarget.overlayMedia{
                    debugLog(overlayMedia)
                    if let type = overlayMedia.type{
                        if(type == "image"){
                            if let local_path = overlayMedia.getLocalPath(){
                                if(FileManager.default.fileExists(atPath: local_path)){
                                    let local_URL = NSURL.fileURL(withPath: local_path)
                                    if (local_URL.pathExtension.lowercased() == "gif") {
                                       if let image = UIImage.gifImageWithURL(local_URL){
                                           overlayImages.append(image)
                                           arTarget.overlayImage = image
                                       }
                                    } else if let image = UIImage(contentsOfFile: local_path){
                                        overlayImages.append(image)
                                        arTarget.overlayImage = image
                                    }
                                }
                            }
                        }
                    }
                }
                
                if let audioMedia = arTarget.audioMedia{
                    debugLog(audioMedia)
                    if let type = audioMedia.type{
                        if(type == "audio"){
                            if let local_path = audioMedia.getLocalPath(){
                                if(FileManager.default.fileExists(atPath: local_path)){
                                    self.audioFilePath = local_path
                                    do {
                                        self.audioPlayer = try AVAudioPlayer(contentsOf: URL(fileURLWithPath: self.audioFilePath))
                                        self.audioPlayer.prepareToPlay()
                                        self.audioPlayer.delegate = self
                                    } catch let error {
                                        debugLog("Can't play the audio file failed with an error \(error.localizedDescription)")
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        debugLog(arTargetTaskMetas)
        for taskMeta in arTargetTaskMetas{
            if(taskMeta.key == TaskMeta.key_ar_target_id){
                if let media = taskMeta.media{
                    debugLog(media)
                    if let type = media.type{
                        if(type == "image"){
                            if let local_path = media.getLocalPath(){
                                if(FileManager.default.fileExists(atPath: local_path)){
                                    if let image = UIImage(contentsOfFile: local_path){
                                        if let cgImage = image.cgImage{
                                            let arImage = ARReferenceImage(cgImage, orientation: .up, physicalWidth: 13.0)
                                            if let title = media.title{
                                                arImage.name = title
                                            }
                                            detectionImages.append(arImage)
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // Hook up status view controller callback(s).
        statusViewController.restartExperienceHandler = { [unowned self] in
            self.restartExperience()
        }
    }
    
    override func viewDidAppear(_ animated: Bool) {
        super.viewDidAppear(animated)
        
        // Prevent the screen from being dimmed to avoid interuppting the AR experience.
        UIApplication.shared.isIdleTimerDisabled = true

        // Start the AR experience
        resetTracking()
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)

        session.pause()
    }

    // MARK: - Session management (Image detection setup)
    
    /// Prevents restarting the session while a restart is in progress.
    var isRestartAvailable = true

    /// Creates a new AR configuration to run on the `session`.
    /// - Tag: ARReferenceImage-Loading
    func resetTracking() {
        let configuration = ARWorldTrackingConfiguration()
        configuration.detectionImages =  Set(detectionImages.map {$0})

        sceneView.scene.rootNode.enumerateChildNodes { (node, stop) in
            node.removeFromParentNode()
        }

        session.run(configuration, options: [.resetTracking, .removeExistingAnchors])

        statusViewController.scheduleMessage("Look around to detect images", inSeconds: 7.5, messageType: .contentPlacement)
    }

    // MARK: - ARSCNViewDelegate (Image detection results)
    /// - Tag: ARImageAnchor-Visualizing
    func renderer(_ renderer: SCNSceneRenderer, didAdd node: SCNNode, for anchor: ARAnchor) {
        guard let imageAnchor = anchor as? ARImageAnchor else { return }
        let referenceImage = imageAnchor.referenceImage
        updateQueue.async {
            
            // Create a plane to visualize the initial position of the detected image.
            let plane = SCNPlane(width: referenceImage.physicalSize.width,
                                 height: referenceImage.physicalSize.height)
            let planeNode = SCNNode(geometry: plane)
            planeNode.opacity = 0.25
            
            /*
             `SCNPlane` is vertically oriented in its local coordinate space, but
             `ARImageAnchor` assumes the image is horizontal in its local space, so
             rotate the plane to match.
             */
            planeNode.eulerAngles.x = -.pi / 2
            
            /*
             Image anchors are not tracked after initial detection, so create an
             animation that limits the duration for which the plane visualization appears.
             */
            planeNode.runAction(self.imageHighlightAction)
            
            // Add the plane visualization to the scene.
            node.addChildNode(planeNode)
            
            //If there is an audio file, play the audio
            if(self.audioPlayer != nil){
                if(self.audioPlayer.isPlaying){
                    self.audioPlayer.stop()
                }
                self.audioPlayer.play()
            }
            
            if let index = self.detectionImages.index(of: referenceImage){
                let arTarget = self.arTargets[index]
                self.recognizedTarget = arTarget
                if(arTarget.overlayImage != nil){
                    self.drawOverlay(image: arTarget.overlayImage, with: imageAnchor, node: node, referenceImage: referenceImage)
                }else{
                    self.drawOverlay(image: UIImage(named: "correct")!, with: imageAnchor, node: node, referenceImage: referenceImage)
                }
            }else{
                self.drawOverlay(image: UIImage(named: "correct")!, with: imageAnchor, node: node, referenceImage: referenceImage)
            }
            
            
         
            let textNode = self.createTitleTextNode(withImageName: referenceImage.name ?? "")
            node.addChildNode(textNode)
            
            //After it's been recognized, send the user out of the ARSession.
            if let delegate = self.delegate {
                delegate.didRecognizeImage()
            }
            
            //End experience
            if(self.recognizedTarget != nil){
                if(self.recognizedTarget.should_auto_close == 1){
                    if(self.recognizedTarget.close_after == 0){
                        DispatchQueue.main.asyncAfter(deadline: .now() + 3.0, execute: {
                            self.endExperience()
                        })
                    }else{
                        DispatchQueue.main.asyncAfter(deadline: .now() + Double(self.recognizedTarget.close_after), execute: {
                            self.endExperience()
                        })
                    }
                    
                }
            }
        }

        DispatchQueue.main.async {
            let imageName = referenceImage.name ?? ""
            self.statusViewController.cancelAllScheduledMessages()
            self.statusViewController.showMessage("Detected image “\(imageName)”")
        }
    }
    
    func drawOverlay(image: UIImage, with imageAnchor: ARImageAnchor, node: SCNNode, referenceImage: ARReferenceImage) {
        let plane = SCNNode(geometry: SCNPlane(width: referenceImage.physicalSize.width,
                                               height: referenceImage.physicalSize.height))
        DispatchQueue.main.async()  { // Must run UIImageView on main thread to prevent locking
            plane.geometry?.firstMaterial?.diffuse.contents = UIImageView(image: image).layer
        }
        plane.geometry?.firstMaterial?.isDoubleSided = true
        plane.position = SCNVector3(0, 0.05, 0)
        plane.eulerAngles.x = -.pi / 2

        node.addChildNode(plane)
    }
    
    // Create a text node to display the name of an artwork.
    func createTitleTextNode(withImageName imageName: String) -> SCNNode {
        let text = SCNText(string: imageName, extrusionDepth: 0.1)
        text.font = UIFont.systemFont(ofSize: 1.0)
        text.flatness = 0.01
        text.firstMaterial?.diffuse.contents = UIColor.white

        let textNode = SCNNode(geometry: text)

        let billboardConstraint = SCNBillboardConstraint()
        billboardConstraint.freeAxes = SCNBillboardAxis.Y
        textNode.constraints = [billboardConstraint]
        
        textNode.scale = SCNVector3(0.05, 0.05, 0.01)
        textNode.eulerAngles.x = -.pi / 2
        
        let (min, max) = textNode.boundingBox
        
        let dx = min.x + 0.5 * (max.x - min.x)
        let dy = min.y + 0.5 * (max.y - min.y)
        let dz = min.z + 0.5 * (max.z - min.z)
        textNode.pivot = SCNMatrix4MakeTranslation(dx, dy, dz)
        textNode.position = SCNVector3(0, 0.08, -0.08)

        return textNode
    }
    
    func drawShipNode(imageAnchor: ARImageAnchor){
        // 1. Load plane's scene.
        let shipScene = SCNScene(named: "art.scnassets/ship.scn")!
        let shipNode = shipScene.rootNode.childNode(withName: "ship", recursively: true)!
        
        // 2. Calculate size based on planeNode's bounding box.
        let (min, max) = shipNode.boundingBox
        let size = SCNVector3Make(max.x - min.x, max.y - min.y, max.z - min.z)
        
        // 3. Calculate the ratio of difference between real image and object size.
        // Ignore Y axis because it will be pointed out of the image.
        let widthRatio = Float(imageAnchor.referenceImage.physicalSize.width)/size.x
        let heightRatio = Float(imageAnchor.referenceImage.physicalSize.height)/size.z
        // Pick smallest value to be sure that object fits into the image.
        let finalRatio = [widthRatio, heightRatio].min()!
        
        // 4. Set transform from imageAnchor data.
        shipNode.transform = SCNMatrix4(imageAnchor.transform)
        
        shipNode.eulerAngles = SCNVector3(-45.degreesToRadians(),
                                          0,
                                          0)
        
        // 5. Animate appearance by scaling model from 0 to previously calculated value.
        let appearanceAction = SCNAction.scale(to: CGFloat(finalRatio), duration: 2.5)
        appearanceAction.timingMode = .easeOut
        // Set initial scale to 0.
        shipNode.scale = SCNVector3Make(0, 0, 0)
        // Add to root node.
        sceneView.scene.rootNode.addChildNode(shipNode)
        // Run the appearance animation.
        let rotateAction = SCNAction.rotateBy(x: 45.degreesToRadians(), y: 0, z: 0, duration: 0.5)
        shipNode.runAction(appearanceAction) {
            shipNode.runAction(rotateAction)
        }
    }

    var imageHighlightAction: SCNAction {
        return .sequence([
            .wait(duration: 0.25),
            .fadeOpacity(to: 0.85, duration: 0.25),
            .fadeOpacity(to: 0.15, duration: 0.25),
            .fadeOpacity(to: 0.85, duration: 0.25),
            .fadeOut(duration: 4.5),
            .removeFromParentNode()
        ])
    }
}

extension Int {
    func degreesToRadians() -> CGFloat {
        return CGFloat(self) * CGFloat.pi / 180.0
    }
}

extension ARViewController : AVAudioPlayerDelegate{
    func audioPlayerDidFinishPlaying(_ player: AVAudioPlayer, successfully flag: Bool) {
        
    }
    
    func audioPlayerDecodeErrorDidOccur(_ player: AVAudioPlayer, error: Error?) {
        debugLog(error.debugDescription)
    }
}
