@php($ru = app()->getLocale() === 'ru')
<div class="chatbot" x-data="{ open: false }" x-on:chatbot-pending.window="$wire.answer()">
    <button type="button" class="chatbot-toggle" x-on:click="open = !open"
            :aria-expanded="open" aria-label="{{ $ru ? 'Чат с помощником' : 'Yordamchi bilan chat' }}">
        <svg x-show="!open" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        <svg x-show="open" x-cloak viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>

    <div class="chatbot-panel" x-show="open" x-cloak x-transition.opacity>
        <div class="chatbot-head">
            <div>
                <div class="chatbot-title">{{ $ru ? 'Помощник Xprinter.uz' : 'Xprinter.uz yordamchisi' }}</div>
                <div class="chatbot-sub">{{ $ru ? 'Отвечает по каталогу и сайту' : 'Katalog va sayt bo\'yicha javob beradi' }}</div>
            </div>
            @if($messages)
                <button type="button" class="chatbot-clear" wire:click="clear">{{ $ru ? 'Очистить' : 'Tozalash' }}</button>
            @endif
        </div>

        <div class="chatbot-body" x-ref="body"
             x-init="$watch('open', v => v && $nextTick(() => $refs.body.scrollTop = $refs.body.scrollHeight))"
             x-effect="$wire.messages; $wire.pending; $nextTick(() => $refs.body.scrollTop = $refs.body.scrollHeight)">
            <div class="chatbot-msg chatbot-msg-bot">
                {{ $ru
                    ? 'Здравствуйте! Спросите о принтерах Xprinter, характеристиках, драйверах или как с нами связаться. Можно писать по-русски или по-узбекски.'
                    : "Assalomu alaykum! Xprinter printerlari, xususiyatlari, drayverlar yoki biz bilan bog'lanish haqida so'rang. O'zbek yoki rus tilida yozishingiz mumkin." }}
            </div>

            @foreach($messages as $msg)
                <div wire:key="msg-{{ $loop->index }}" @class(['chatbot-msg', 'chatbot-msg-user' => $msg['role'] === 'user', 'chatbot-msg-bot' => $msg['role'] !== 'user'])>
                    {!! $this->format($msg['content']) !!}
                </div>
            @endforeach

            @if($pending)
                <div class="chatbot-msg chatbot-msg-bot chatbot-typing"><span></span><span></span><span></span></div>
            @endif
        </div>

        <form class="chatbot-form" wire:submit="send">
            <input type="text" wire:model="input" maxlength="1000" autocomplete="off"
                   placeholder="{{ $ru ? 'Напишите вопрос…' : 'Savolingizni yozing…' }}" @disabled($pending)>
            <button type="submit" @disabled($pending) aria-label="{{ $ru ? 'Отправить' : 'Yuborish' }}">
                <svg viewBox="0 0 24 24"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
            </button>
        </form>
    </div>
</div>
