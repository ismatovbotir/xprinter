<!DOCTYPE html>

<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xprint Systems — Precision Print Technology</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;400;500;600;700&family=Manrope:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg: #FFFFFF;
            --bg-soft: #F4F8FE;
            --bg-blue: #EAF2FD;
            --surface: #FFFFFF;
            --surface-alt: #F8FAFD;
            --line: #E3EBF5;
            --line-hi: #C9D6E8;
            --ink: #0A1B3D;
            --ink-soft: #2C3E5C;
            --muted: #6B7B95;
            --faint: #A5B0C4;

            ``` --blue: #0066FF;
            --blue-hi: #1A75FF;
            --blue-deep: #0040B8;
            --blue-soft: #DCE9FB;
            --cyan: #00B6E8;
            --green: #00C896;
            ```
        }

        - {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(–bg);
            color: var(–ink);
            font-family: ‘Manrope’, -apple-system, sans-serif;
            font-size: 15px;
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        .mono {
            font-family: ‘JetBrains Mono’, monospace;
        }

        .display {
            font-family: ‘Unbounded’, sans-serif;
            letter-spacing: -0.02em;
        }

        /* Ambient blue glow */
        body::before {
            content: ‘’;
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 90% 60% at 50% -20%, rgba(0, 102, 255, 0.10), transparent 60%),
                radial-gradient(ellipse 70% 50% at 100% 30%, rgba(0, 182, 232, 0.06), transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        .grid-bg {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(0, 40, 120, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 40, 120, 0.04) 1px, transparent 1px);
            background-size: 64px 64px;
            pointer-events: none;
            z-index: 0;
            mask-image: radial-gradient(ellipse at top, black 30%, transparent 80%);
        }

        .container {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 32px;
            position: relative;
            z-index: 1;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 20px;
            }
        }

        /* Nav */
        nav {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid var(–line);
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: ‘Unbounded’, sans-serif;
            font-weight: 600;
            font-size: 17px;
            letter-spacing: -0.02em;
            color: var(–ink);
            text-decoration: none;
        }

        .logo-mark {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, var(–blue), var(–cyan));
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 102, 255, 0.3);
        }

        .logo-mark::before,
        .logo-mark::after {
            content: ‘’;
            position: absolute;
            background: rgba(255, 255, 255, 0.95);
        }

        .logo-mark::before {
            top: 7px;
            left: 7px;
            right: 7px;
            height: 2px;
            border-radius: 1px;
        }

        .logo-mark::after {
            bottom: 7px;
            left: 7px;
            right: 7px;
            height: 2px;
            border-radius: 1px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
        }

        @media (max-width: 900px) {
            .nav-links .link-group {
                display: none;
            }
        }

        .link-group {
            display: flex;
            gap: 28px;
        }

        .nav-links a {
            color: var(–ink-soft);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color .15s;
        }

        .nav-links a:hover {
            color: var(–blue);
        }

        .nav-cta {
            padding: 10px 18px;
            background: var(–blue);
            color: #FFF;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all .15s;
            box-shadow: 0 4px 14px rgba(0, 102, 255, 0.25);
        }

        .nav-cta:hover {
            background: var(–blue-hi);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(0, 102, 255, 0.35);
        }

        /* Hero */
        .hero {
            padding: 80px 0 110px;
            position: relative;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        @media (max-width: 960px) {
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .hero {
                padding: 48px 0 64px;
            }
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 11px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(–blue-deep);
            padding: 8px 14px;
            border: 1px solid var(–blue-soft);
            border-radius: 100px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            margin-bottom: 28px;
        }

        .eyebrow .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(–green);
            box-shadow: 0 0 10px var(–green);
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        h1.hero-title {
            font-family: ‘Unbounded’, sans-serif;
            font-weight: 400;
            font-size: clamp(42px, 5.8vw, 74px);
            line-height: 1.0;
            letter-spacing: -0.04em;
            margin-bottom: 24px;
            color: var(–ink);
        }

        h1.hero-title .blue-grad {
            background: linear-gradient(120deg, var(–blue) 20%, var(–cyan));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-style: italic;
            font-family: ‘Manrope’, sans-serif;
            font-weight: 300;
        }

        .hero-sub {
            color: var(–muted);
            font-size: 17px;
            max-width: 52ch;
            margin-bottom: 36px;
        }

        .cta-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 24px;
            border-radius: 100px;
            font-family: ‘Manrope’, sans-serif;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all .2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(–blue);
            color: #FFF;
            box-shadow: 0 6px 20px rgba(0, 102, 255, 0.3);
        }

        .btn-primary:hover {
            background: var(–blue-hi);
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(0, 102, 255, 0.4);
        }

        .btn-ghost {
            background: var(–surface);
            color: var(–ink);
            border-color: var(–line-hi);
        }

        .btn-ghost:hover {
            border-color: var(–blue);
            color: var(–blue);
        }

        .hero-meta {
            margin-top: 56px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            padding-top: 28px;
            border-top: 1px solid var(–line);
        }

        @media (max-width: 600px) {
            .hero-meta {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .meta-item .num {
            font-family: ‘Unbounded’, sans-serif;
            font-weight: 500;
            font-size: 28px;
            letter-spacing: -0.02em;
            color: var(–ink);
        }

        .meta-item .num .unit {
            color: var(–blue);
            font-size: 18px;
            margin-left: 2px;
        }

        .meta-item .lbl {
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 10px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(–muted);
            margin-top: 4px;
        }

        /* Hero viz */
        .hero-viz {
            position: relative;
            aspect-ratio: 1/1;
            max-height: 560px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .viz-glow {
            position: absolute;
            inset: 10%;
            background: radial-gradient(circle, rgba(0, 102, 255, 0.15), transparent 60%);
            filter: blur(40px);
            border-radius: 50%;
        }

        .viz-ring {
            position: absolute;
            inset: 8%;
            border-radius: 50%;
            border: 1px solid var(–line);
            opacity: 0.8;
        }

        .viz-ring.r2 {
            inset: 22%;
            border-color: var(–line-hi);
        }

        .viz-ring.r3 {
            inset: 36%;
            border-color: var(–blue);
            opacity: 0.4;
            border-style: dashed;
            animation: spin 60s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .printer-illo {
            position: relative;
            width: 90%;
            max-width: 440px;
            z-index: 2;
            filter: drop-shadow(0 30px 60px rgba(0, 40, 120, 0.18));
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-12px);
            }
        }

        .viz-label {
            position: absolute;
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 10px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(–ink-soft);
            background: var(–surface);
            border: 1px solid var(–line);
            padding: 7px 12px;
            border-radius: 6px;
            white-space: nowrap;
            z-index: 3;
            box-shadow: 0 4px 16px rgba(0, 40, 120, 0.08);
        }

        .viz-label::before {
            content: ‘’;
            position: absolute;
            width: 36px;
            height: 1px;
            background: var(–blue);
            top: 50%;
        }

        .viz-label.top-left {
            top: 12%;
            left: 0;
        }

        .viz-label.top-left::before {
            right: -40px;
        }

        .viz-label.bot-right {
            bottom: 18%;
            right: 0;
        }

        .viz-label.bot-right::before {
            left: -40px;
        }

        .viz-label .v {
            color: var(–blue);
            margin-left: 6px;
            font-weight: 600;
        }

        /* Sections */
        section {
            padding: 96px 0;
            position: relative;
        }

        @media (max-width: 768px) {
            section {
                padding: 56px 0;
            }
        }

        .sec-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 48px;
            gap: 24px;
            flex-wrap: wrap;
        }

        .sec-head h2 {
            font-family: ‘Unbounded’, sans-serif;
            font-weight: 400;
            font-size: clamp(32px, 4vw, 48px);
            letter-spacing: -0.03em;
            line-height: 1.05;
            max-width: 22ch;
            color: var(–ink);
        }

        .sec-head .num {
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 12px;
            letter-spacing: 0.15em;
            color: var(–blue);
            font-weight: 600;
        }

        .sec-head .desc {
            max-width: 320px;
            color: var(–muted);
            font-size: 14px;
            margin-top: 8px;
        }

        /* Products */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            background: var(–line);
            border: 1px solid var(–line);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 40, 120, 0.05);
        }

        @media (max-width: 900px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
        }

        .product-card {
            background: var(–surface);
            padding: 28px 24px 24px;
            position: relative;
            cursor: pointer;
            transition: all .25s;
            min-height: 280px;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            background: var(–bg-soft);
        }

        .product-card .idx {
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 10px;
            letter-spacing: 0.15em;
            color: var(–faint);
            margin-bottom: 20px;
        }

        .product-card .icon {
            width: 52px;
            height: 52px;
            background: var(–bg-blue);
            border: 1px solid var(–blue-soft);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 40px;
            transition: all .25s;
        }

        .product-card:hover .icon {
            background: var(–blue);
            border-color: var(–blue);
            transform: scale(1.05) rotate(-3deg);
        }

        .product-card .icon svg {
            width: 26px;
            height: 26px;
            stroke: var(–blue);
            fill: none;
            stroke-width: 1.6;
            transition: stroke .25s;
        }

        .product-card:hover .icon svg {
            stroke: #FFF;
        }

        .product-card h3 {
            font-family: ‘Unbounded’, sans-serif;
            font-weight: 500;
            font-size: 19px;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
            color: var(–ink);
        }

        .product-card p {
            color: var(–muted);
            font-size: 14px;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .product-card .tags {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .product-card .tag {
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 10px;
            letter-spacing: 0.05em;
            color: var(–blue-deep);
            padding: 4px 9px;
            background: var(–bg-blue);
            border-radius: 4px;
            font-weight: 500;
        }

        .product-card .arrow {
            position: absolute;
            top: 24px;
            right: 24px;
            width: 34px;
            height: 34px;
            border: 1px solid var(–line-hi);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(–ink-soft);
            background: var(–surface);
            transition: all .25s;
        }

        .product-card:hover .arrow {
            background: var(–blue);
            border-color: var(–blue);
            color: #FFF;
            transform: rotate(-45deg);
        }

        /* Flagship */
        .flagship {
            background: linear-gradient(135deg, var(–bg-soft), var(–bg-blue));
            border: 1px solid var(–blue-soft);
            border-radius: 28px;
            padding: 56px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        @media (max-width: 900px) {
            .flagship {
                grid-template-columns: 1fr;
                padding: 32px;
            }
        }

        .flagship::before {
            content: ‘’;
            position: absolute;
            top: -30%;
            right: -10%;
            width: 60%;
            height: 200%;
            background: radial-gradient(ellipse, rgba(0, 102, 255, 0.12), transparent 60%);
            pointer-events: none;
        }

        .flag-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 11px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(–blue);
            background: var(–surface);
            padding: 7px 12px;
            border-radius: 100px;
            border: 1px solid var(–blue-soft);
            margin-bottom: 20px;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .flag-title {
            font-family: ‘Unbounded’, sans-serif;
            font-weight: 500;
            font-size: clamp(28px, 4vw, 40px);
            letter-spacing: -0.03em;
            line-height: 1;
            margin-bottom: 16px;
            color: var(–ink);
            position: relative;
            z-index: 1;
        }

        .flag-desc {
            color: var(–muted);
            font-size: 16px;
            margin-bottom: 28px;
            position: relative;
            z-index: 1;
        }

        .spec-list {
            list-style: none;
            margin-bottom: 28px;
            position: relative;
            z-index: 1;
            background: var(–surface);
            border-radius: 14px;
            padding: 4px 20px;
            border: 1px solid var(–line);
        }

        .spec-list li {
            display: flex;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px solid var(–line);
            font-size: 14px;
        }

        .spec-list li:last-child {
            border: none;
        }

        .spec-list .k {
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(–muted);
        }

        .spec-list .v {
            font-weight: 600;
            color: var(–ink);
        }

        .flag-viz {
            position: relative;
            aspect-ratio: 1/1;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        .flag-viz svg {
            width: 100%;
            max-width: 400px;
            filter: drop-shadow(0 30px 50px rgba(0, 40, 120, 0.2));
        }

        /* OEM / WHY */
        .why-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        @media (max-width: 900px) {
            .why-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .why-card {
            padding: 28px 24px;
            background: var(–surface);
            border: 1px solid var(–line);
            border-radius: 16px;
            transition: all .25s;
            position: relative;
            overflow: hidden;
        }

        .why-card::after {
            content: ‘’;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(–blue);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s;
        }

        .why-card:hover {
            border-color: var(–blue-soft);
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0, 40, 120, 0.08);
        }

        .why-card:hover::after {
            transform: scaleX(1);
        }

        .why-num {
            font-family: ‘Unbounded’, sans-serif;
            font-weight: 500;
            font-size: 48px;
            letter-spacing: -0.04em;
            line-height: 1;
            margin-bottom: 12px;
            background: linear-gradient(135deg, var(–blue), var(–cyan));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .why-num .unit {
            font-size: 24px;
            margin-left: 2px;
        }

        .why-lbl {
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(–ink);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .why-desc {
            font-size: 13px;
            color: var(–muted);
        }

        /* OEM workflow */
        .oem-wrap {
            background: var(–surface);
            border: 1px solid var(–line);
            border-radius: 28px;
            padding: 48px;
            box-shadow: 0 10px 40px rgba(0, 40, 120, 0.04);
        }

        @media (max-width: 768px) {
            .oem-wrap {
                padding: 32px 24px;
            }
        }

        .oem-flow {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            position: relative;
        }

        @media (max-width: 900px) {
            .oem-flow {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .oem-flow {
                grid-template-columns: 1fr;
            }
        }

        .oem-step {
            background: var(–bg-soft);
            border: 1px solid var(–line);
            border-radius: 14px;
            padding: 20px;
            position: relative;
            transition: all .25s;
        }

        .oem-step:hover {
            border-color: var(–blue);
            background: var(–bg-blue);
            transform: translateY(-3px);
        }

        .oem-step .step-num {
            width: 32px;
            height: 32px;
            background: var(–blue);
            color: #FFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 14px;
        }

        .oem-step h4 {
            font-family: ‘Unbounded’, sans-serif;
            font-weight: 500;
            font-size: 14px;
            letter-spacing: -0.01em;
            margin-bottom: 6px;
            color: var(–ink);
        }

        .oem-step p {
            font-size: 12px;
            color: var(–muted);
            line-height: 1.5;
        }

        /* Tech / SDK */
        .tech {
            background: var(–ink);
            border-radius: 28px;
            padding: 56px;
            color: #FFF;
            position: relative;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .tech {
                padding: 36px 24px;
            }
        }

        .tech::before {
            content: ‘’;
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 40% at 80% 20%, rgba(0, 102, 255, 0.4), transparent 70%),
                radial-gradient(ellipse 60% 40% at 0% 100%, rgba(0, 182, 232, 0.2), transparent 70%);
            pointer-events: none;
        }

        .tech-grid {
            position: relative;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 48px;
        }

        @media (max-width: 900px) {
            .tech-grid {
                grid-template-columns: 1fr;
            }
        }

        .tech .eyebrow {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.15);
            color: #B6CDFF;
            backdrop-filter: blur(10px);
        }

        .tech h3 {
            font-family: ‘Unbounded’, sans-serif;
            font-weight: 500;
            font-size: 36px;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 16px;
        }

        .tech p {
            color: #B6CDFF;
            margin-bottom: 24px;
        }

        .proto-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            list-style: none;
        }

        .proto-list li {
            padding: 8px 14px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 100px;
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 12px;
            color: #FFF;
            backdrop-filter: blur(10px);
        }

        .proto-label {
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 10px;
            letter-spacing: 0.1em;
            color: #8FA9D6;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .terminal {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 12.5px;
            backdrop-filter: blur(10px);
        }

        .term-head {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .term-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #4A5568;
        }

        .term-dot:nth-child(2) {
            background: #F6AD55;
        }

        .term-dot:nth-child(3) {
            background: #00C896;
        }

        .term-title {
            margin-left: 8px;
            color: #8FA9D6;
            font-size: 11px;
            letter-spacing: 0.05em;
        }

        .term-body {
            padding: 20px;
            line-height: 1.75;
            color: #E2E8F0;
        }

        .term-body .pr {
            color: #6FA8FF;
        }

        .term-body .cm {
            color: #6B7B95;
        }

        .term-body .kw {
            color: #00DCAA;
        }

        .term-body .st {
            color: #FFB547;
        }

        /* News */
        .news-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        @media (max-width: 900px) {
            .news-grid {
                grid-template-columns: 1fr;
            }
        }

        .news-card {
            background: var(–surface);
            border: 1px solid var(–line);
            border-radius: 18px;
            overflow: hidden;
            transition: all .25s;
            cursor: pointer;
        }

        .news-card:hover {
            border-color: var(–blue-soft);
            transform: translateY(-3px);
            box-shadow: 0 16px 40px rgba(0, 40, 120, 0.08);
        }

        .news-thumb {
            aspect-ratio: 16/10;
            background: linear-gradient(135deg, var(–bg-blue), var(–bg-soft));
            position: relative;
            overflow: hidden;
        }

        .news-thumb svg {
            width: 100%;
            height: 100%;
        }

        .news-thumb .tag {
            position: absolute;
            top: 14px;
            left: 14px;
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 10px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(–blue);
            background: var(–surface);
            padding: 5px 10px;
            border-radius: 100px;
            font-weight: 600;
            border: 1px solid var(–blue-soft);
        }

        .news-body {
            padding: 20px 22px 22px;
        }

        .news-date {
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 11px;
            color: var(–muted);
            margin-bottom: 8px;
            letter-spacing: 0.05em;
        }

        .news-card h4 {
            font-family: ‘Unbounded’, sans-serif;
            font-weight: 500;
            font-size: 16px;
            letter-spacing: -0.01em;
            line-height: 1.3;
            margin-bottom: 10px;
            color: var(–ink);
        }

        .news-card p {
            font-size: 13px;
            color: var(–muted);
        }

        /* CTA */
        .cta-band {
            background: linear-gradient(120deg, var(–blue) 0%, var(–blue-deep) 50%, #001F66 100%);
            border-radius: 28px;
            padding: 64px 48px;
            position: relative;
            overflow: hidden;
            color: #FFF;
        }

        @media (max-width: 768px) {
            .cta-band {
                padding: 40px 28px;
            }
        }

        .cta-band::before {
            content: ‘’;
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.06) 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: radial-gradient(ellipse at center, black, transparent 70%);
        }

        .cta-band::after {
            content: ‘’;
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 182, 232, 0.4), transparent 70%);
            top: -150px;
            right: -100px;
            pointer-events: none;
        }

        .cta-band-inner {
            position: relative;
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            gap: 32px;
            align-items: center;
            z-index: 1;
        }

        @media (max-width: 768px) {
            .cta-band-inner {
                grid-template-columns: 1fr;
            }
        }

        .cta-band h3 {
            font-family: ‘Unbounded’, sans-serif;
            font-weight: 400;
            font-size: clamp(28px, 3.5vw, 42px);
            letter-spacing: -0.03em;
            line-height: 1.05;
        }

        .cta-band-cta {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        @media (max-width: 768px) {
            .cta-band-cta {
                justify-content: flex-start;
            }
        }

        .btn-white {
            background: #FFF;
            color: var(–blue);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-white:hover {
            background: var(–ink);
            color: #FFF;
        }

        .btn-outline-w {
            background: transparent;
            border-color: rgba(255, 255, 255, 0.4);
            color: #FFF;
        }

        .btn-outline-w:hover {
            border-color: #FFF;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Footer */
        footer {
            padding: 64px 0 32px;
            border-top: 1px solid var(–line);
            margin-top: 64px;
            background: var(–bg-soft);
        }

        .foot-grid {
            display: grid;
            grid-template-columns: 1.3fr repeat(4, 1fr);
            gap: 32px;
            margin-bottom: 48px;
        }

        @media (max-width: 900px) {
            .foot-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 560px) {
            .foot-grid {
                grid-template-columns: 1fr;
            }
        }

        .foot-brand .logo {
            margin-bottom: 16px;
        }

        .foot-brand p {
            color: var(–muted);
            font-size: 14px;
            max-width: 36ch;
        }

        .foot-col h5 {
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 11px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(–ink-soft);
            margin-bottom: 16px;
            font-weight: 600;
        }

        .foot-col ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .foot-col a {
            color: var(–ink);
            text-decoration: none;
            font-size: 14px;
            transition: color .15s;
        }

        .foot-col a:hover {
            color: var(–blue);
        }

        .foot-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 24px;
            border-top: 1px solid var(–line);
            font-family: ‘JetBrains Mono’, monospace;
            font-size: 11px;
            letter-spacing: 0.05em;
            color: var(–muted);
            flex-wrap: wrap;
            gap: 12px;
        }

        /* Reveal */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .7s ease, transform .7s cubic-bezier(.2, .8, .2, 1);
        }

        .reveal.in {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

</head>

<body>

    <div class="grid-bg"></div>

    <!-- NAV -->

    <nav>
        <div class="container">
            <div class="nav-inner">
                <a href="#" class="logo">
                    <div class="logo-mark"></div>
                    <span>XPRINT<span style="color: var(--blue);">/</span>SYS</span>
                </a>
                <div class="nav-links">
                    <div class="link-group">
                        <a href="#products">Продукты</a>
                        <a href="#oem">OEM/ODM</a>
                        <a href="#tech">Технологии</a>
                        <a href="#news">Новости</a>
                    </div>
                    <button class="nav-cta">Запросить прайс →</button>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO -->

    <section class="hero">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-copy">
                    <div class="eyebrow reveal">
                        <span class="dot"></span>
                        EST. 2006 · 200+ СТРАН · ISO 9001:2015
                    </div>
                    <h1 class="hero-title reveal">
                        Печатные<br>
                        технологии<br>
                        для <span class="blue-grad">мирового</span><br>
                        ритейла.
                    </h1>
                    <p class="hero-sub reveal">
                        Термопринтеры чеков, этикеток и промышленной маркировки. OEM-производство полного цикла — от
                        схемотехники до SMT, с собственной R&D-лабораторией и 8 международными сертификациями.
                    </p>
                    <div class="cta-row reveal">
                        <button class="btn btn-primary">Каталог продукции <span>→</span></button>
                        <button class="btn btn-ghost">Скачать SDK</button>
                    </div>
                    <div class="hero-meta reveal">
                        <div class="meta-item">
                            <div class="num">200<span class="unit">+</span></div>
                            <div class="lbl">Стран</div>
                        </div>
                        <div class="meta-item">
                            <div class="num">6.3<span class="unit">M</span></div>
                            <div class="lbl">Шт / год</div>
                        </div>
                        <div class="meta-item">
                            <div class="num">19<span class="unit">лет</span></div>
                            <div class="lbl">R&D опыт</div>
                        </div>
                        <div class="meta-item">
                            <div class="num">0.3<span class="unit">%</span></div>
                            <div class="lbl">Брак</div>
                        </div>
                    </div>
                </div>

                ```
                <div class="hero-viz reveal">
                    <div class="viz-glow"></div>
                    <div class="viz-ring r1"></div>
                    <div class="viz-ring r2"></div>
                    <div class="viz-ring r3"></div>
                    <div class="viz-label top-left">Термоголовка<span class="v">203 DPI</span></div>
                    <div class="viz-label bot-right">Скорость<span class="v">250 mm/s</span></div>

                    <svg class="printer-illo" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="body" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#FFFFFF" />
                                <stop offset="100%" stop-color="#E8EFF9" />
                            </linearGradient>
                            <linearGradient id="topFace" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#F4F8FE" />
                                <stop offset="100%" stop-color="#D4E1F2" />
                            </linearGradient>
                            <linearGradient id="screen" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#0A1B3D" />
                                <stop offset="100%" stop-color="#1A2D5A" />
                            </linearGradient>
                            <linearGradient id="paper" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#FFFFFF" />
                                <stop offset="100%" stop-color="#E8EBF0" />
                            </linearGradient>
                            <linearGradient id="accentBar" x1="0" y1="0" x2="1"
                                y2="0">
                                <stop offset="0%" stop-color="#0066FF" />
                                <stop offset="100%" stop-color="#00B6E8" />
                            </linearGradient>
                        </defs>

                        <ellipse cx="200" cy="360" rx="140" ry="10" fill="#0066FF"
                            opacity="0.12" />

                        <path
                            d="M 80 300 L 80 180 Q 80 160 100 160 L 300 160 Q 320 160 320 180 L 320 300 Q 320 320 300 320 L 100 320 Q 80 320 80 300 Z"
                            fill="url(#body)" stroke="#C9D6E8" stroke-width="1" />

                        <path d="M 100 160 Q 100 120 140 110 L 260 110 Q 300 120 300 160 L 100 160 Z"
                            fill="url(#topFace)" stroke="#C9D6E8" stroke-width="1" />

                        <rect x="130" y="108" width="140" height="8" rx="2" fill="#0A1B3D"
                            stroke="#C9D6E8" stroke-width="0.5" />

                        <path d="M 150 108 L 150 60 L 250 60 L 250 108 Z" fill="url(#paper)" stroke="#C9D6E8"
                            stroke-width="0.5" />
                        <line x1="162" y1="72" x2="238" y2="72" stroke="#0A1B3D"
                            stroke-width="1" />
                        <line x1="162" y1="80" x2="220" y2="80" stroke="#6B7B95"
                            stroke-width="0.8" />
                        <line x1="162" y1="86" x2="230" y2="86" stroke="#6B7B95"
                            stroke-width="0.8" />
                        <line x1="162" y1="92" x2="210" y2="92" stroke="#6B7B95"
                            stroke-width="0.8" />
                        <g fill="#0A1B3D">
                            <rect x="170" y="96" width="2" height="8" />
                            <rect x="174" y="96" width="1" height="8" />
                            <rect x="177" y="96" width="3" height="8" />
                            <rect x="182" y="96" width="1" height="8" />
                            <rect x="185" y="96" width="2" height="8" />
                            <rect x="189" y="96" width="3" height="8" />
                            <rect x="194" y="96" width="1" height="8" />
                            <rect x="197" y="96" width="2" height="8" />
                            <rect x="201" y="96" width="3" height="8" />
                            <rect x="206" y="96" width="1" height="8" />
                            <rect x="209" y="96" width="2" height="8" />
                            <rect x="213" y="96" width="3" height="8" />
                            <rect x="218" y="96" width="1" height="8" />
                            <rect x="221" y="96" width="2" height="8" />
                            <rect x="225" y="96" width="3" height="8" />
                        </g>

                        <rect x="110" y="180" width="100" height="50" rx="6" fill="url(#screen)"
                            stroke="#C9D6E8" stroke-width="1" />
                        <text x="120" y="200" font-family="JetBrains Mono" font-size="9" fill="#00DCAA"
                            font-weight="600">READY</text>
                        <text x="120" y="214" font-family="JetBrains Mono" font-size="7"
                            fill="#8FA9D6">XP-T80N</text>
                        <text x="120" y="224" font-family="JetBrains Mono" font-size="7"
                            fill="#8FA9D6">ETH·USB·BT</text>
                        <circle cx="195" cy="195" r="2" fill="#00DCAA">
                            <animate attributeName="opacity" values="1;0.3;1" dur="2s"
                                repeatCount="indefinite" />
                        </circle>

                        <circle cx="240" cy="200" r="9" fill="#FFF" stroke="#C9D6E8"
                            stroke-width="1" />
                        <circle cx="240" cy="200" r="3" fill="#0066FF" />
                        <circle cx="270" cy="200" r="9" fill="#FFF" stroke="#C9D6E8"
                            stroke-width="1" />
                        <circle cx="270" cy="200" r="3" fill="#A5B0C4" />
                        <circle cx="300" cy="200" r="9" fill="#FFF" stroke="#C9D6E8"
                            stroke-width="1" />
                        <circle cx="300" cy="200" r="3" fill="#A5B0C4" />

                        <rect x="180" y="260" width="40" height="3" rx="1" fill="url(#accentBar)" />
                        <text x="200" y="285" text-anchor="middle" font-family="Unbounded" font-size="10"
                            font-weight="600" fill="#0A1B3D">XPRINT</text>

                        <g stroke="#C9D6E8" stroke-width="1">
                            <line x1="100" y1="295" x2="120" y2="295" />
                            <line x1="100" y1="300" x2="120" y2="300" />
                            <line x1="100" y1="305" x2="120" y2="305" />
                            <line x1="280" y1="295" x2="300" y2="295" />
                            <line x1="280" y1="300" x2="300" y2="300" />
                            <line x1="280" y1="305" x2="300" y2="305" />
                        </g>
                    </svg>
                </div>
            </div>
            ```

        </div>
    </section>

    <!-- PRODUCTS -->

    <section id="products">
        <div class="container">
            <div class="sec-head reveal">
                <h2>Полный парк<br>печатных решений</h2>
                <div>
                    <div class="num">01 — ПРОДУКТЫ</div>
                    <p class="desc">Шесть продуктовых линеек с едиными SDK и протоколами. ESC/POS · TSPL · ZPL · EPL.
                    </p>
                </div>
            </div>

            ```
            <div class="products-grid reveal">
                <div class="product-card">
                    <div class="idx">/ 001</div>
                    <div class="icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="4" y="7" width="16" height="12" rx="1" />
                            <path d="M7 7V4h10v3M8 11h8M8 14h8" />
                        </svg>
                    </div>
                    <div class="arrow">→</div>
                    <h3>Receipt Printers</h3>
                    <p>Принтеры чеков 58 / 76 / 80 мм для POS-терминалов, кухонь и касс самообслуживания.</p>
                    <div class="tags">
                        <span class="tag">58MM</span><span class="tag">80MM</span><span
                            class="tag">ESC/POS</span>
                    </div>
                </div>

                <div class="product-card">
                    <div class="idx">/ 002</div>
                    <div class="icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="8" width="18" height="10" rx="1" />
                            <rect x="7" y="12" width="10" height="3" />
                        </svg>
                    </div>
                    <div class="arrow">→</div>
                    <h3>Label Printers</h3>
                    <p>Этикеточные принтеры 2" / 3" / 4" — термо и термотрансфер для логистики и retail.</p>
                    <div class="tags">
                        <span class="tag">TSPL</span><span class="tag">ZPL</span><span class="tag">4
                            INCH</span>
                    </div>
                </div>

                <div class="product-card">
                    <div class="idx">/ 003</div>
                    <div class="icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="6" y="3" width="12" height="18" rx="2" />
                            <circle cx="12" cy="17" r="1" />
                        </svg>
                    </div>
                    <div class="arrow">→</div>
                    <h3>Mobile Printers</h3>
                    <p>Беспроводные принтеры с аккумулятором. Bluetooth, Wi-Fi, NFC. Курьеры и полевые работы.</p>
                    <div class="tags">
                        <span class="tag">BT 5.0</span><span class="tag">IP54</span><span
                            class="tag">8H</span>
                    </div>
                </div>

                <div class="product-card">
                    <div class="idx">/ 004</div>
                    <div class="icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 19h16M6 19V9l6-5 6 5v10M10 19v-6h4v6" />
                        </svg>
                    </div>
                    <div class="arrow">→</div>
                    <h3>Industrial</h3>
                    <p>Промышленные принтеры для круглосуточного склада и производственной маркировки.</p>
                    <div class="tags">
                        <span class="tag">24/7</span><span class="tag">300 DPI</span><span
                            class="tag">METAL</span>
                    </div>
                </div>

                <div class="product-card">
                    <div class="idx">/ 005</div>
                    <div class="icon">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M4 11a8 8 0 0 1 16 0v3M4 14v4a2 2 0 0 0 2 2h2v-7H4M20 14v4a2 2 0 0 1-2 2h-2v-7h4" />
                        </svg>
                    </div>
                    <div class="arrow">→</div>
                    <h3>Cloud & Panel</h3>
                    <p>Принтеры с прямым подключением к облаку и встраиваемые модули для киосков.</p>
                    <div class="tags">
                        <span class="tag">CLOUD</span><span class="tag">OEM</span><span
                            class="tag">API</span>
                    </div>
                </div>

                <div class="product-card">
                    <div class="idx">/ 006</div>
                    <div class="icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="5" y="7" width="14" height="10" rx="1" />
                            <path d="M8 7V5M16 7V5M9 12h6" />
                        </svg>
                    </div>
                    <div class="arrow">→</div>
                    <h3>Wristband</h3>
                    <p>Принтеры браслетов для медицины, отелей, аквапарков. Водостойкий материал.</p>
                    <div class="tags">
                        <span class="tag">HOSPITAL</span><span class="tag">HOTEL</span><span
                            class="tag">RFID</span>
                    </div>
                </div>
            </div>
            ```

        </div>
    </section>

    <!-- FLAGSHIP -->

    <section>
        <div class="container">
            <div class="flagship reveal">
                <div>
                    <div class="flag-badge">● FLAGSHIP · XP-T80N SERIES</div>
                    <h2 class="flag-title">Built for 1 million<br>receipts per head.</h2>
                    <p class="flag-desc">Индустриальный 80мм термопринтер с автокалибровкой резака, онлайн-обновлением
                        прошивки и полной поддержкой ESC/POS.</p>
                    <ul class="spec-list">
                        <li><span class="k">Ширина печати</span><span class="v">72 мм</span></li>
                        <li><span class="k">Скорость</span><span class="v">250 mm/s</span></li>
                        <li><span class="k">Разрешение</span><span class="v">203 DPI</span></li>
                        <li><span class="k">Ресурс головки</span><span class="v">150 km</span></li>
                        <li><span class="k">Интерфейсы</span><span class="v">USB · LAN · BT · Serial</span>
                        </li>
                        <li><span class="k">Ресурс резака</span><span class="v">1 500 000 циклов</span></li>
                    </ul>
                    <div class="cta-row">
                        <button class="btn btn-primary">Тех. паспорт</button>
                        <button class="btn btn-ghost">3D-модель</button>
                    </div>
                </div>
                <div class="flag-viz">
                    <svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="f-body" x1="0" y1="0" x2="0"
                                y2="1">
                                <stop offset="0%" stop-color="#FFFFFF" />
                                <stop offset="100%" stop-color="#DCE9FB" />
                            </linearGradient>
                            <linearGradient id="f-top" x1="0" y1="0" x2="0"
                                y2="1">
                                <stop offset="0%" stop-color="#F8FAFD" />
                                <stop offset="100%" stop-color="#C9D6E8" />
                            </linearGradient>
                            <linearGradient id="f-side" x1="0" y1="0" x2="1"
                                y2="0">
                                <stop offset="0%" stop-color="#B6CDFF" />
                                <stop offset="100%" stop-color="#8FA9D6" />
                            </linearGradient>
                            <linearGradient id="f-paper" x1="0" y1="0" x2="0"
                                y2="1">
                                <stop offset="0%" stop-color="#FFFFFF" />
                                <stop offset="100%" stop-color="#D4E1F2" />
                            </linearGradient>
                            <linearGradient id="f-accent" x1="0" y1="0" x2="1"
                                y2="0">
                                <stop offset="0%" stop-color="#0066FF" />
                                <stop offset="100%" stop-color="#00B6E8" />
                            </linearGradient>
                        </defs>

                        ```
                        <ellipse cx="200" cy="360" rx="160" ry="12" fill="#0066FF"
                            opacity="0.15" />

                        <path d="M 60 310 L 60 170 L 150 130 L 340 130 L 340 270 L 250 310 Z" fill="url(#f-body)"
                            stroke="#C9D6E8" stroke-width="1" />
                        <path d="M 60 170 L 150 130 L 340 130 L 250 170 Z" fill="url(#f-top)" stroke="#C9D6E8"
                            stroke-width="1" />
                        <path d="M 250 170 L 340 130 L 340 270 L 250 310 Z" fill="url(#f-side)" stroke="#C9D6E8"
                            stroke-width="1" opacity="0.8" />

                        <rect x="100" y="150" width="140" height="6" rx="1" fill="#0A1B3D"
                            transform="skewX(-22)" />

                        <path d="M 130 155 Q 150 100 200 80 L 300 50 L 310 130 Z" fill="url(#f-paper)"
                            stroke="#A5B0C4" stroke-width="0.5" />
                        <g transform="rotate(-18, 230, 100)">
                            <rect x="170" y="70" width="90" height="2" fill="#0A1B3D" />
                            <rect x="170" y="78" width="70" height="1.5" fill="#6B7B95" />
                            <rect x="170" y="84" width="80" height="1.5" fill="#6B7B95" />
                            <rect x="170" y="90" width="60" height="1.5" fill="#6B7B95" />
                            <g fill="#0A1B3D">
                                <rect x="180" y="98" width="2" height="12" />
                                <rect x="184" y="98" width="1" height="12" />
                                <rect x="187" y="98" width="3" height="12" />
                                <rect x="192" y="98" width="1" height="12" />
                                <rect x="195" y="98" width="2" height="12" />
                                <rect x="200" y="98" width="3" height="12" />
                                <rect x="206" y="98" width="1" height="12" />
                                <rect x="210" y="98" width="2" height="12" />
                                <rect x="215" y="98" width="3" height="12" />
                                <rect x="221" y="98" width="1" height="12" />
                                <rect x="225" y="98" width="2" height="12" />
                                <rect x="230" y="98" width="3" height="12" />
                                <rect x="236" y="98" width="1" height="12" />
                                <rect x="240" y="98" width="2" height="12" />
                            </g>
                        </g>

                        <rect x="90" y="210" width="130" height="50" rx="6" fill="#0A1B3D"
                            stroke="#C9D6E8" stroke-width="1" />
                        <text x="100" y="228" font-family="JetBrains Mono" font-size="10" fill="#00DCAA"
                            font-weight="600">● ONLINE</text>
                        <text x="100" y="243" font-family="JetBrains Mono" font-size="8" fill="#8FA9D6">XP-T80N ·
                            v2.4.1</text>
                        <text x="100" y="254" font-family="JetBrains Mono" font-size="8"
                            fill="#8FA9D6">192.168.1.42</text>

                        <circle cx="85" cy="285" r="7" fill="#0066FF" />
                        <circle cx="105" cy="285" r="7" fill="#FFF" stroke="#C9D6E8" />
                        <circle cx="125" cy="285" r="7" fill="#FFF" stroke="#C9D6E8" />

                        <text x="230" y="250" font-family="Unbounded" font-size="14" font-weight="600"
                            fill="#0A1B3D">XPRINT</text>
                        <rect x="230" y="256" width="60" height="2" fill="url(#f-accent)" />
                    </svg>
                </div>
            </div>
            ```

        </div>
    </section>

    <!-- WHY US -->

    <section>
        <div class="container">
            <div class="sec-head reveal">
                <h2>Почему выбирают<br>Xprint Systems</h2>
                <div>
                    <div class="num">02 — ПРОИЗВОДСТВО</div>
                    <p class="desc">Собственное SMT, пресс-формы, сборка. 100% вертикальная интеграция и полный
                        контроль качества.</p>
                </div>
            </div>
            <div class="why-grid reveal">
                <div class="why-card">
                    <div class="why-num">19<span class="unit">лет</span></div>
                    <div class="why-lbl">Опыт R&D</div>
                    <div class="why-desc">Лидер рынка термопринтеров в Китае с 2006 года.</div>
                </div>
                <div class="why-card">
                    <div class="why-num">6.3<span class="unit">M</span></div>
                    <div class="why-lbl">Шт / год</div>
                    <div class="why-desc">Производственная мощность по всем линейкам продуктов.</div>
                </div>
                <div class="why-card">
                    <div class="why-num">10<span class="unit">%</span></div>
                    <div class="why-lbl">Revenue → R&D</div>
                    <div class="why-desc">Ежегодные инвестиции в разработки и патенты.</div>
                </div>
                <div class="why-card">
                    <div class="why-num">8<span class="unit">серт</span></div>
                    <div class="why-lbl">Сертификации</div>
                    <div class="why-desc">CCC · CE · FCC · RoHS · KC · SAA · BIS · BSMI.</div>
                </div>
                <div class="why-card">
                    <div class="why-num">0.3<span class="unit">%</span></div>
                    <div class="why-lbl">Уровень брака</div>
                    <div class="why-desc">Собственная QC-лаборатория — единственная в индустрии.</div>
                </div>
                <div class="why-card">
                    <div class="why-num">7<span class="unit">дн</span></div>
                    <div class="why-lbl">Lead time</div>
                    <div class="why-desc">Партии до 1000 шт — отгрузка за 7–10 рабочих дней.</div>
                </div>
                <div class="why-card">
                    <div class="why-num">180<span class="unit">мод</span></div>
                    <div class="why-lbl">Новых моделей / год</div>
                    <div class="why-desc">Активное обновление линейки под запросы рынка.</div>
                </div>
                <div class="why-card">
                    <div class="why-num">2K<span class="unit">+</span></div>
                    <div class="why-lbl">Сотрудники</div>
                    <div class="why-desc">Инженеры, OEM-команда, контроль качества, поддержка.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- OEM WORKFLOW -->

    <section id="oem">
        <div class="container">
            <div class="oem-wrap reveal">
                <div class="sec-head" style="margin-bottom: 36px;">
                    <h2 style="font-size: clamp(28px, 3.5vw, 40px);">OEM & ODM<br>сервис под ключ</h2>
                    <div>
                        <div class="num">03 — WORKFLOW</div>
                        <p class="desc">От требований до серии. Полный цикл за 5 шагов.</p>
                    </div>
                </div>

                ```
                <div class="oem-flow">
                    <div class="oem-step">
                        <div class="step-num">01</div>
                        <h4>Требования</h4>
                        <p>Сбор и анализ требований заказчика, оценка задач.</p>
                    </div>
                    <div class="oem-step">
                        <div class="step-num">02</div>
                        <h4>Дизайн</h4>
                        <p>Разработка чертежей и согласование с клиентом.</p>
                    </div>
                    <div class="oem-step">
                        <div class="step-num">03</div>
                        <h4>Прототип</h4>
                        <p>Контракт, разработка платы и сборка сэмпла.</p>
                    </div>
                    <div class="oem-step">
                        <div class="step-num">04</div>
                        <h4>Тестирование</h4>
                        <p>Внутренние тесты + проверка на стороне заказчика.</p>
                    </div>
                    <div class="oem-step">
                        <div class="step-num">05</div>
                        <h4>Серия</h4>
                        <p>Запуск массового производства и упаковка.</p>
                    </div>
                </div>
            </div>
            ```

        </div>
    </section>

    <!-- TECH / SDK -->

    <section id="tech">
        <div class="container">
            <div class="tech reveal">
                <div class="tech-grid">
                    <div>
                        <div class="eyebrow" style="margin-bottom: 24px;">
                            <span class="dot" style="background: #00DCAA; box-shadow: 0 0 10px #00DCAA;"></span>
                            04 — DEV STACK
                        </div>
                        <h3>SDK и протоколы<br>для всех платформ.</h3>
                        <p>Бесшовная интеграция с вашим POS, WMS или мобильным приложением. Драйверы, документация и
                            примеры кода — в открытом доступе.</p>

                        ```
                        <div style="margin-bottom: 20px;">
                            <div class="proto-label">ПРОТОКОЛЫ</div>
                            <ul class="proto-list">
                                <li>ESC/POS</li>
                                <li>TSPL</li>
                                <li>ZPL</li>
                                <li>EPL</li>
                                <li>DPL</li>
                                <li>CPCL</li>
                            </ul>
                        </div>

                        <div>
                            <div class="proto-label">ПЛАТФОРМЫ</div>
                            <ul class="proto-list">
                                <li>Windows</li>
                                <li>macOS</li>
                                <li>Linux</li>
                                <li>Android</li>
                                <li>iOS</li>
                                <li>Web USB</li>
                            </ul>
                        </div>
                    </div>

                    <div class="terminal">
                        <div class="term-head">
                            <span class="term-dot"></span>
                            <span class="term-dot"></span>
                            <span class="term-dot"></span>
                            <span class="term-title">xprint-sdk · example.js</span>
                        </div>
                        <div class="term-body">
                            <span class="cm">// Подключение принтера по USB</span><br>
                            <span class="kw">import</span> { <span class="pr">XPrinter</span> } <span
                                class="kw">from</span> <span class="st">'@xprint/sdk'</span>;<br><br>
                            <span class="kw">const</span> printer = <span class="kw">await</span> <span
                                class="pr">XPrinter</span>.<span class="pr">connect</span>({<br>
                            &nbsp;&nbsp;iface: <span class="st">'usb'</span>,<br>
                            &nbsp;&nbsp;model: <span class="st">'XP-T80N'</span><br>
                            });<br><br>
                            <span class="kw">await</span> printer<br>
                            &nbsp;&nbsp;.<span class="pr">align</span>(<span class="st">'center'</span>)<br>
                            &nbsp;&nbsp;.<span class="pr">text</span>(<span class="st">'XPRINT.SYS'</span>, {
                            size: <span class="st">2</span> })<br>
                            &nbsp;&nbsp;.<span class="pr">feed</span>(<span class="st">1</span>)<br>
                            &nbsp;&nbsp;.<span class="pr">barcode</span>(<span
                                class="st">'1234567890'</span>)<br>
                            &nbsp;&nbsp;.<span class="pr">cut</span>()<br>
                            &nbsp;&nbsp;.<span class="pr">print</span>();<br><br>
                            <span class="cm">// → OK [2 ticks, 128 bytes]</span>
                        </div>
                    </div>
                </div>
            </div>
            ```

        </div>
    </section>

    <!-- NEWS -->

    <section id="news">
        <div class="container">
            <div class="sec-head reveal">
                <h2>Новости и<br>выставки</h2>
                <div>
                    <div class="num">05 — UPDATES</div>
                    <p class="desc">Свежие анонсы, выставки и индустриальные обновления.</p>
                </div>
            </div>

            ```
            <div class="news-grid reveal">
                <div class="news-card">
                    <div class="news-thumb">
                        <span class="tag">Выставка</span>
                        <svg viewBox="0 0 400 250" preserveAspectRatio="xMidYMid slice">
                            <rect width="400" height="250" fill="#EAF2FD" />
                            <circle cx="100" cy="125" r="60" fill="#0066FF" opacity="0.15" />
                            <circle cx="300" cy="125" r="80" fill="#00B6E8" opacity="0.15" />
                            <rect x="150" y="80" width="100" height="90" rx="8" fill="#FFF"
                                stroke="#C9D6E8" />
                            <rect x="160" y="100" width="80" height="3" fill="#0066FF" />
                            <rect x="160" y="110" width="60" height="2" fill="#A5B0C4" />
                            <rect x="160" y="118" width="70" height="2" fill="#A5B0C4" />
                            <text x="200" y="155" text-anchor="middle" font-family="Unbounded" font-size="14"
                                font-weight="600" fill="#0A1B3D">NRF 2026</text>
                        </svg>
                    </div>
                    <div class="news-body">
                        <div class="news-date">2026 · 16 ЯНВ</div>
                        <h4>Новые POS-решения на выставке NRF 2026</h4>
                        <p>Представили POS-линейку с интегрированными технологиями быстрой печати и облачной
                            синхронизацией.</p>
                    </div>
                </div>

                <div class="news-card">
                    <div class="news-thumb">
                        <span class="tag">Продукт</span>
                        <svg viewBox="0 0 400 250" preserveAspectRatio="xMidYMid slice">
                            <rect width="400" height="250" fill="#F4F8FE" />
                            <rect x="100" y="60" width="200" height="130" rx="12" fill="#FFF"
                                stroke="#C9D6E8" />
                            <rect x="120" y="80" width="160" height="40" rx="6" fill="#0A1B3D" />
                            <text x="200" y="105" text-anchor="middle" font-family="JetBrains Mono" font-size="11"
                                fill="#00DCAA" font-weight="600">XP-Q890K</text>
                            <rect x="130" y="135" width="140" height="4" fill="#0066FF" />
                            <rect x="130" y="145" width="100" height="3" fill="#A5B0C4" />
                            <rect x="130" y="153" width="120" height="3" fill="#A5B0C4" />
                            <circle cx="160" cy="170" r="4" fill="#0066FF" />
                            <circle cx="180" cy="170" r="4" fill="#C9D6E8" />
                            <circle cx="200" cy="170" r="4" fill="#C9D6E8" />
                        </svg>
                    </div>
                    <div class="news-body">
                        <div class="news-date">2026 · 8 МАР</div>
                        <h4>Релиз XP-Q890K для супермаркетов</h4>
                        <p>Высокоскоростной 80мм принтер чеков с автокалибровкой резака и обновлением прошивки по сети.
                        </p>
                    </div>
                </div>

                <div class="news-card">
                    <div class="news-thumb">
                        <span class="tag">Партнёрство</span>
                        <svg viewBox="0 0 400 250" preserveAspectRatio="xMidYMid slice">
                            <rect width="400" height="250" fill="#EAF2FD" />
                            <circle cx="150" cy="125" r="50" fill="none" stroke="#0066FF"
                                stroke-width="2" />
                            <circle cx="250" cy="125" r="50" fill="none" stroke="#00B6E8"
                                stroke-width="2" />
                            <rect x="180" y="115" width="40" height="20" rx="4" fill="#FFF"
                                stroke="#0066FF" stroke-width="1.5" />
                            <text x="200" y="129" text-anchor="middle" font-family="Unbounded" font-size="9"
                                font-weight="600" fill="#0A1B3D">API</text>
                        </svg>
                    </div>
                    <div class="news-body">
                        <div class="news-date">2026 · 22 ФЕВ</div>
                        <h4>Открытое API для разработчиков</h4>
                        <p>Запустили публичную документацию и SDK для интеграции принтеров в любые POS- и ERP-системы.
                        </p>
                    </div>
                </div>
            </div>
            ```

        </div>
    </section>

    <!-- CTA -->

    <section>
        <div class="container">
            <div class="cta-band reveal">
                <div class="cta-band-inner">
                    <h3>Нужна OEM-сборка под ваш бренд?<br>От 500 единиц, от 7 дней.</h3>
                    <div class="cta-band-cta">
                        <button class="btn btn-white">Запросить сэмпл →</button>
                        <button class="btn btn-outline-w">Связаться с R&D</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->

    <footer>
        <div class="container">
            <div class="foot-grid">
                <div class="foot-brand">
                    <a href="#" class="logo">
                        <div class="logo-mark"></div>
                        <span>XPRINT<span style="color: var(--blue);">/</span>SYS</span>
                    </a>
                    <p>Печатная техника для POS, логистики и промышленной маркировки. OEM & ODM с 2006 года.</p>
                </div>
                <div class="foot-col">
                    <h5>Продукты</h5>
                    <ul>
                        <li><a href="#">Receipt</a></li>
                        <li><a href="#">Label</a></li>
                        <li><a href="#">Mobile</a></li>
                        <li><a href="#">Industrial</a></li>
                        <li><a href="#">Cloud</a></li>
                    </ul>
                </div>
                <div class="foot-col">
                    <h5>Ресурсы</h5>
                    <ul>
                        <li><a href="#">Документация</a></li>
                        <li><a href="#">SDK & Drivers</a></li>
                        <li><a href="#">Сертификаты</a></li>
                        <li><a href="#">Примеры кода</a></li>
                        <li><a href="#">Changelog</a></li>
                    </ul>
                </div>
                <div class="foot-col">
                    <h5>Компания</h5>
                    <ul>
                        <li><a href="#">О нас</a></li>
                        <li><a href="#">Блог</a></li>
                        <li><a href="#">Выставки</a></li>
                        <li><a href="#">Вакансии</a></li>
                        <li><a href="#">Контакты</a></li>
                    </ul>
                </div>
                <div class="foot-col">
                    <h5>Контакты</h5>
                    <ul>
                        <li><a href="#">sales@xprintsys.com</a></li>
                        <li><a href="#">+86 756 393 2978</a></li>
                        <li style="color: var(--muted); font-size: 13px;">Zhuhai City,<br>Guangdong, China</li>
                    </ul>
                </div>
            </div>
            <div class="foot-bottom">
                <div>© 2026 XPRINT SYSTEMS · ISO 9001:2015 · CE / FCC / RoHS</div>
                <div>v 4.2.1 · BUILD 20260424</div>
            </div>
        </div>
    </footer>

    <script>
        const io = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('in');
                    io.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.08,
            rootMargin: '0px 0px -60px 0px'
        });

        document.querySelectorAll('.reveal').forEach((el, i) => {
            el.style.transitionDelay = `${Math.min(i * 0.05, 0.3)}s`;
            io.observe(el);
        });

        setTimeout(() => {
            document.querySelectorAll('.hero .reveal').forEach((el, i) => {
                setTimeout(() => el.classList.add('in'), i * 80);
            });
        }, 100);
    </script>

</body>

</html>
