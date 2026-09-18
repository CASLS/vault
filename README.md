# VAuLT: Mixed-Reality Experience Toolkit

**VAuLT (Virtual and Augmented Reality for Language Learning Training)** is a toolkit for building place-based, mixed-reality experiences. A mobile companion app and a backend authoring environment work together, so educators, facilitators, and designers can assemble quests that blend digital tasks (text, AR image targets, speech-to-text, embedded media, web links) with physical places and analog materials. No coding required.

VAuLT was built over nearly ten years at the Center for Applied Second Language Studies (CASLS) at the University of Oregon. It's been used for K–5 language curricula, Fulbright pre-departure orientations, large-scale public AR installations, and mixed-reality strategic planning sessions.

Scope of this release: "VAuLT" names both the broader mixed-reality toolkit (software, analog materials, and a decade of designed experiences) and the experience design platform at its core. This repository contains the platform only (the mobile companion app and the backend authoring environment), which the University of Oregon has approved for open-source release. The experiences built with the platform (e.g., Escape from Byru'Moxia, I-Agents), the analog manipulatives and puzzle templates, and other curricular content remain copyrighted works and are not included in this release. They are referenced in this README as publicly available evidence of what the platform can do.

---

## Origins & Provenance

VAuLT was created at **CASLS, University of Oregon**. CASLS is a Title VI National Foreign Language Resource Center funded by the U.S. Department of Education. Its work covers applied research, pedagogical innovation, and technology for language teaching and learning.

The toolkit evolved through three generations:

1. **Quest App (2019)**: a mobile scavenger-hunt app first deployed at the CALICO 2019 conference in Montréal (University of Oregon - Innovation ID 026).
2. **VAuLT Mixed-Reality Mobile Application, Toolkit Version (2021)**: a full redesign to support task delivery, image recognition, VR capabilities, speech-to-text, audio/video, embedded links and images, and completion feedback (University of Oregon - DIS-22-011). Funded in part by the UO VPRI Innovation Fund.
3. **VAuLT End-User Development Platform (Backend)**: the authoring environment that structures task and experience design (University of Oregon - DIS-25/001).

**Contributors** across the platform's history include: Julie Sykes, Stephanie Knight, Mandy Gettler, Mitra Nite (Dunn), Carl Burnstein, Scott Morrison, Ryan Chang, and Christopher Daradics, with contracted design/development support from Twenty Ideas.

Funding acknowledgment
Portions of VAuLT were developed with support from the **U.S. Department of Education** (Title VI, EPCS #26485, 2018–2022) and the **University of Oregon Office of the Vice President for Research and Innovation (VPRI) Innovation Fund**, with additional internal CASLS support. The contents of this repository do not necessarily represent the policy of the U.S. Department of Education, and you should not assume endorsement by the Federal Government.

### Open-source release

This code is released under the **Apache License 2.0** with the support of the University of Oregon's Office of the Vice President for Research and Innovation. The university supports open models for research software, and the innovators chose Apache 2.0 to preserve attribution to the university as originator while permitting use, modification, and commercial services around the open core. Four IP disclosures document the platform's provenance. The university's approval covers the platform code only: the analog manipulatives and puzzle templates (DIS-22-016) and the experiences and curricular content built with the platform are copyrighted works under separate consideration and are not included in or licensed by this release.

---

## By the Numbers

Usage across platform-mediated and analog VAuLT deployments (through spring 2026). The experiences named below were built with the platform and demonstrate its use in the field; they are not themselves part of this open-source release:

| Metric | Value |
| :---- | :---- |
| Total user accounts (designers, facilitators, participants) | ~500 |
| Experience designers (quest creators) | 21 |
| Total quests built | 141 |
| Unique users who opened at least one quest | 496 |
| Recorded task-response interactions | 12,600+ |
| Participants across featured experiences | 2,000+ |
| Implementations across key experiences | 388 |

### Selected deployments

- **Fulbright Pre-Departure Orientation**: VAuLT-enabled experiences used annually for 6–7 years; *Escape from Byru'Moxia* alone has reached 950 participants (61% completion).
- **I-Agents K–5 curriculum**: 300 participants, 57% completion, 360 facilitations with institutional partners.
- **Oregon Experience Laboratory (Oregon22 World Athletics Championships)**: public AR activation, 150–200 visitors, sponsored by Travel Oregon, UO College of Design, and the City of Eugene. Recognized with an ASLA Oregon Design Awards Honor Award and the Pacific Horticulture 2024 Design Futurist Award; covered by OregonLive and KLCC.
- **Pragmatics at Play (CALICO 2024, Carnegie Mellon University)**: card-trading conference game, 175 participants.
- **Games2Teach Collaboratory**: ~50 educators completed a structured, multi-step experience-design sequence using VAuLT materials.
- **Mavericks convening format (1.0–4.0)**: mixed-reality strategy and planning formats for institutional audiences, including the Global Studies Institute Fall Luncheon (120 attendees) and the VPRI-supported *CASLS in the Cloud* strategic visioning roundtable.
- **Japanese Business Pragmatics Experience**: prototype module + language-agnostic test harness; 27 participants, 71% completion.

---

## Technical Overview

### Architecture

- **Mobile companion app (iOS)**: Swift. Delivers tasks and experiences to participants: text prompts, AR image-target recognition, speech-to-text, audio/video playback, embedded links and images, 360° media, and completion feedback.
- **Backend authoring platform**: PHP (Yii framework). An end-user development environment where designers compose quests from task primitives, organize them into experiences, and manage participants and facilitation.

### Task primitives

Experiences are assembled from a small set of interaction primitives (distribution across all digitally delivered experiences):

| Task type |
| :---- |
| Text |
| AR image target |
| Web URL |
| Speech-to-text |
| 360° image / video |

### What's in this repository

This is a monorepo containing the two components of the VAuLT platform:

| Component | Path | Stack | Setup guide |
| :---- | :---- | :---- | :---- |
| Backend authoring platform | [`vault_web/`](vault_web/) | PHP (Yii2), MySQL/MariaDB, Docker | [vault_web/README.md](vault_web/README.md) |
| Mobile companion app | [`vault_ios/`](vault_ios/) | Swift, CocoaPods, iOS 13+ | [vault_ios/README.md](vault_ios/README.md) |

Each component's README covers requirements, self-hosted setup (Docker for the backend, Xcode/CocoaPods for iOS), and the placeholders you'll need to fill in with your own values (server host, Google OAuth credentials, etc.) before either one will run.

---

## License

Licensed under the [Apache License 2.0](LICENSE.txt). Copyright University of Oregon.

You may use, modify, distribute, and build commercial services on this code. Attribution to the University of Oregon as originator is preserved per the license terms. The license covers the platform code in this repository only; VAuLT experiences and analog materials referenced in this README are not part of this release.

## Acknowledgments

VAuLT exists because of a decade of collaboration at CASLS: designers, facilitators, teachers, students, Fulbright cohorts, and public visitors who played, tested, and taught with it. Thanks to the University of Oregon OVPRI and the Division of Global Engagement for supporting the open-source release, and to the U.S. Department of Education Title VI program for foundational funding.