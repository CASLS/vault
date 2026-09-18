//
//  TimeIntervalExtension.swift
//  Vault
//
//  Created by Carl Burnstein on 3/26/19.
//  Copyright © 2019 CASLS.
//

import Foundation

extension TimeInterval {
    struct DateComponents {
        static let formatterPositional: DateComponentsFormatter = {
            let formatter = DateComponentsFormatter()
            formatter.allowedUnits = [.minute,.second]
            formatter.unitsStyle = DateComponentsFormatter.UnitsStyle.positional
            formatter.zeroFormattingBehavior = .pad
            return formatter
        }()
    }
    var positionalTime: String {
        return DateComponents.formatterPositional.string(from: self) ?? ""
    }
}
