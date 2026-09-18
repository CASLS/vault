//
//  AudioPlayerViewController.swift
//  Vault
//
//  Created by Carl Burnstein on 3/26/19.
//  Copyright © 2019 CASLS.
//

import UIKit
import AVKit
import AVFoundation

class AudioPlayerViewController: UIViewController {

    @IBOutlet weak var playPauseButton: UIButton!
    @IBOutlet weak var progressSlider: UISlider!
    @IBOutlet weak var timeLabel: UILabel!
    
    var filePath : String = ""
    var audioPlayer: AVAudioPlayer!
    var progressLink : CADisplayLink? = nil
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        playPauseButton.imageView?.contentMode = .scaleAspectFit
        do {
            audioPlayer = try AVAudioPlayer(contentsOf: URL(fileURLWithPath: filePath))
            audioPlayer.prepareToPlay()
            audioPlayer.delegate = self
            updateProgress()
        } catch let error {
            debugLog("Can't play the audio file failed with an error \(error.localizedDescription)")
        }
    }
    
    @IBAction func playPauseButtonTouched(_ sender: Any) {
        if(audioPlayer.isPlaying){
            audioPlayer.pause()
            playPauseButton.setImage(UIImage(named: "play"), for: .normal)
        }else{
            progressLink = CADisplayLink(target: self,
                                         selector: #selector(self.updateProgress))
            if let progressLink = progressLink {
                progressLink.add(to: RunLoop.current, forMode: RunLoop.Mode.default)
            }
            audioPlayer.play()
            playPauseButton.setImage(UIImage(named: "pause"), for: .normal)
        }
    }
    
    @IBAction func scrubAction(_ sender: Any) {
        // if the track was playing store true, so we can restart playing after changing the track position
        var wasPlaying : Bool = false
        if audioPlayer.isPlaying == true {
            audioPlayer.pause()
            wasPlaying = true
        }
        audioPlayer.currentTime = TimeInterval(round(progressSlider.value))
        updateProgress()
        // starts playing track again it it had been playing
        if (wasPlaying == true) {
            audioPlayer.play()
            wasPlaying = false
        }
    }
    
    @objc func updateProgress() {
        progressSlider.minimumValue = 0.0
        progressSlider.maximumValue = Float(audioPlayer.duration)
        progressSlider.setValue(Float(audioPlayer.currentTime), animated: true)
        timeLabel.text = audioPlayer.currentTime.positionalTime + "/" + audioPlayer.duration.positionalTime
    }
    
    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
    }
    */

}

extension AudioPlayerViewController : AVAudioPlayerDelegate {
    func audioPlayerDidFinishPlaying(_ player: AVAudioPlayer, successfully flag: Bool) {
        if let progressLink = progressLink {
            progressLink.invalidate()
            audioPlayer.currentTime = 0.0
            updateProgress()
            progressSlider.setValue(0, animated: false)
            playPauseButton.setImage(UIImage(named: "play"), for: .normal)
        }
    }
    
    func audioPlayerDecodeErrorDidOccur(_ player: AVAudioPlayer, error: Error?) {
        if let progressLink = progressLink {
            progressLink.invalidate()
        }
    }
}
