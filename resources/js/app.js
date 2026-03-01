import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const codeBlocks = document.querySelectorAll('pre');

    codeBlocks.forEach((pre) => {
        if (!pre.parentElement.classList.contains('relative')) {
            const wrapper = document.createElement('div');
            wrapper.className = 'relative group';
            pre.parentNode.insertBefore(wrapper, pre);
            wrapper.appendChild(pre);
        }

        const wrapper = pre.parentElement;
        const copyButton = document.createElement('button');
        copyButton.className = 'absolute top-2 right-2 px-3 py-1.5 bg-gray-700/80 text-white border border-white/20 rounded-md text-xs font-medium cursor-pointer opacity-0 group-hover:opacity-100 transition-all duration-200 ease-in-out flex items-center gap-1 hover:bg-gray-700';
        copyButton.innerHTML = `<svg class="copy-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg> <span class="copy-text">Copy</span>`;

        copyButton.addEventListener('click', () => {
            const code = pre.querySelector('code');
            const text = code ? code.textContent : pre.textContent;

            navigator.clipboard.writeText(text).then(() => {
                copyButton.classList.remove('bg-gray-700/80', 'border-white/20', 'hover:bg-gray-700');
                copyButton.classList.add('!bg-emerald-500/90', 'border-emerald-500');
                copyButton.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <span>Copied!</span>`;

                setTimeout(() => {
                    copyButton.classList.add('bg-gray-700/80', 'border-white/20', 'hover:bg-gray-700');
                    copyButton.classList.remove('!bg-emerald-500/90', 'border-emerald-500');
                    copyButton.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg><span class="copy-text">Copy</span>`;
                }, 2000);
            }).catch((err) => {
                console.error('Failed to copy:', err);
            });
        });

        wrapper.appendChild(copyButton);
    });

    // Tab switching functionality
    const tabButtons = document.querySelectorAll('[data-tab]');
    const tabContents = document.querySelectorAll('[data-tab-content]');

    tabButtons.forEach(button => {
        button.addEventListener('click', function () {
            const targetTab = this.dataset.tab;

            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
                btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            });

            // Add active class to clicked button
            this.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            this.classList.add('active', 'border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');

            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('active');
            });

            // Show target tab content
            const targetContent = document.querySelector(`[data-tab-content="${targetTab}"]`);
            if (targetContent) {
                targetContent.classList.remove('hidden');
                targetContent.classList.add('active');
            }
        });
    });
});
