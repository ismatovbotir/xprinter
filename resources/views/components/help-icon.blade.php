@props(['article', 'translation', 'placement' => 'icon'])

@if($translation && $article->is_active)
    @if($placement === 'tooltip')
        <span class="help-icon" title="{{ $translation->title }}" data-placement="tooltip">
            <svg viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 16v-4M12 8h.01"/>
            </svg>
            <span class="help-tooltip">
                <strong>{{ $translation->title }}</strong>
                <div style="margin-top:8px;font-size:12px;line-height:1.4">
                    {!! $translation->content !!}
                </div>
            </span>
        </span>
    @elseif($placement === 'modal')
        <button type="button" class="help-icon" data-placement="modal" data-article-id="{{ $article->id }}" title="{{ $translation->title }}"
                onclick="document.getElementById('help-modal-{{ $article->id }}').style.display='flex'">
            <svg viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 16v-4M12 8h.01"/>
            </svg>
        </button>

        <div id="help-modal-{{ $article->id }}" class="help-modal-overlay"
             onclick="if(event.target===this) this.style.display='none'">
            <div class="help-modal-box">
                <div class="help-modal-header">
                    <strong>{{ $translation->title }}</strong>
                    <button type="button" class="help-modal-close"
                            onclick="document.getElementById('help-modal-{{ $article->id }}').style.display='none'">&times;</button>
                </div>
                <div class="help-modal-content">
                    {!! $translation->content !!}
                </div>
            </div>
        </div>
    @elseif($placement === 'sidebar')
        <div class="help-sidebar">
            <div class="help-sidebar-header">{{ $translation->title }}</div>
            <div class="help-sidebar-content">
                {!! $translation->content !!}
            </div>
        </div>
    @else
        <!-- icon (default) -->
        <span class="help-icon" title="{{ $translation->title }}" data-placement="icon">
            <svg viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 16v-4M12 8h.01"/>
            </svg>
        </span>
    @endif

    <style>
        .help-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            cursor: help;
            color: var(--blue);
            position: relative;
        }

        .help-icon svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            stroke-width: 1.8;
            fill: none;
        }

        .help-icon:hover {
            color: var(--blue-hi);
        }

        /* Tooltip style */
        .help-icon[data-placement="tooltip"] {
            position: relative;
        }

        .help-tooltip {
            display: none;
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: var(--ink);
            color: #fff;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13px;
            white-space: normal;
            width: 240px;
            margin-bottom: 8px;
            z-index: 50;
            box-shadow: 0 8px 24px rgba(10, 27, 61, 0.2);
        }

        .help-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 6px;
            background: var(--ink);
            border-radius: 1px;
        }

        .help-icon[data-placement="tooltip"]:hover .help-tooltip {
            display: block;
        }

        /* Sidebar style */
        .help-sidebar {
            background: var(--bg-soft);
            border-left: 3px solid var(--blue);
            padding: 12px 16px;
            margin: 12px 0;
            border-radius: 4px;
        }

        .help-sidebar-header {
            font-weight: 600;
            color: var(--blue);
            font-size: 13px;
            margin-bottom: 8px;
        }

        .help-sidebar-content {
            font-size: 12px;
            color: var(--ink-soft);
            line-height: 1.5;
        }

        .help-sidebar-content strong {
            color: var(--ink);
        }

        .help-sidebar-content a {
            color: var(--blue);
            text-decoration: none;
        }

        .help-sidebar-content a:hover {
            text-decoration: underline;
        }

        /* Modal style */
        .help-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(10, 27, 61, 0.45);
            backdrop-filter: blur(2px);
            z-index: 9998;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .help-modal-box {
            background: #fff;
            border-radius: 16px;
            width: 100%;
            max-width: 440px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 24px 64px rgba(10, 27, 61, 0.18);
        }

        .help-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--line);
            font-size: 14px;
            color: var(--ink);
        }

        .help-modal-close {
            background: none;
            border: none;
            font-size: 20px;
            line-height: 1;
            color: var(--muted);
            cursor: pointer;
            padding: 0 4px;
        }

        .help-modal-close:hover {
            color: var(--ink);
        }

        .help-modal-content {
            padding: 20px;
            font-size: 13.5px;
            color: var(--ink-soft);
            line-height: 1.6;
        }

        .help-modal-content a {
            color: var(--blue);
        }
    </style>
@endif
