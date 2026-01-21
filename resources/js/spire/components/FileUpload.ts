import { FileUploadInstance } from '../types';
import { instances, emit } from '../core/registry';

export class FileUpload implements FileUploadInstance {
  #el: HTMLElement;
  #input: HTMLInputElement;
  #dropzone: HTMLElement | null = null;
  #preview: HTMLElement | null = null;
  #files: File[] = [];
  #maxSize: number;
  #maxFiles: number;
  #accept: string;

  constructor(el: HTMLElement) {
    this.#el = el;
    
    // Find or create input
    let input = el.querySelector('input[type="file"]') as HTMLInputElement | null;
    if (!input) {
      input = document.createElement('input');
      input.type = 'file';
      el.appendChild(input);
    }
    this.#input = input;
    
    this.#dropzone = el.querySelector('[data-dropzone]') || el;
    this.#preview = el.querySelector('[data-preview]');
    this.#maxSize = parseInt(el.dataset.maxSize || '10485760'); // 10MB default
    this.#maxFiles = parseInt(el.dataset.maxFiles || '10');
    this.#accept = el.dataset.accept || this.#input.accept || '*/*';
    
    // Set input attributes
    this.#input.accept = this.#accept;
    if (el.dataset.multiple === 'true') {
      this.#input.multiple = true;
    }

    this.#setupDropzone();
    this.#setupListeners();
    instances.set(el, this);
  }

  #setupDropzone(): void {
    // If element already has dropzone content, use it as dropzone
    if (this.#el.children.length > 1 || (this.#el.children.length === 1 && this.#el.children[0] !== this.#input)) {
      // Element has content - use it as dropzone wrapper
      this.#dropzone = this.#el;
    } else if (!this.#el.querySelector('[data-dropzone]')) {
      const dropzone = document.createElement('div');
      dropzone.className = 'border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center transition-colors hover:border-blue-400 dark:hover:border-blue-500';
      dropzone.dataset.dropzone = '';
      dropzone.innerHTML = `
        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
        </svg>
        <p class="text-gray-600 dark:text-gray-400 mb-2">Arraste arquivos aqui ou</p>
        <button type="button" class="text-blue-500 hover:text-blue-600 font-medium">clique para selecionar</button>
      `;
      this.#el.appendChild(dropzone);
      this.#dropzone = dropzone;
    }

    // Create preview container if not exists
    if (!this.#preview) {
      this.#preview = document.createElement('div');
      this.#preview.className = 'grid grid-cols-2 md:grid-cols-4 gap-4 mt-4';
      this.#preview.dataset.preview = '';
      this.#el.appendChild(this.#preview);
    }

    // Hide original input
    this.#input.style.cssText = 'position:absolute;opacity:0;pointer-events:none;';
  }

  #setupListeners(): void {
    // Click to select
    this.#dropzone?.addEventListener('click', (e) => {
      // Don't trigger if clicking on remove button or preview items
      const target = e.target as HTMLElement;
      if (target.closest('[data-remove]') || target.closest('[data-file-index]')) return;
      this.#input.click();
    });

    // Drag and drop - need to track drag enter/leave depth for nested elements
    let dragDepth = 0;

    this.#dropzone?.addEventListener('dragenter', (e) => {
      e.preventDefault();
      e.stopPropagation();
      dragDepth++;
      this.#dropzone?.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
    });

    this.#dropzone?.addEventListener('dragover', (e) => {
      e.preventDefault();
      e.stopPropagation();
      if (e.dataTransfer) {
        e.dataTransfer.dropEffect = 'copy';
      }
    });

    this.#dropzone?.addEventListener('dragleave', (e) => {
      e.preventDefault();
      e.stopPropagation();
      dragDepth--;
      if (dragDepth === 0) {
        this.#dropzone?.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
      }
    });

    this.#dropzone?.addEventListener('drop', (e) => {
      e.preventDefault();
      e.stopPropagation();
      dragDepth = 0;
      this.#dropzone?.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
      
      const files = Array.from(e.dataTransfer?.files || []);
      this.#addFiles(files);
    });

    // Input change
    this.#input.addEventListener('change', () => {
      const files = Array.from(this.#input.files || []);
      this.#addFiles(files);
    });
  }

  #addFiles(files: File[]): void {
    for (const file of files) {
      if (this.#files.length >= this.#maxFiles) {
        emit(this.#el, 'upload:max-files', { max: this.#maxFiles });
        break;
      }

      if (file.size > this.#maxSize) {
        emit(this.#el, 'upload:file-too-large', { file, maxSize: this.#maxSize });
        continue;
      }

      this.#files.push(file);
      this.#renderPreview(file, this.#files.length - 1);
    }

    emit(this.#el, 'upload:files-added', { files: this.#files });
  }

  #renderPreview(file: File, index: number): void {
    if (!this.#preview) return;

    const item = document.createElement('div');
    item.className = 'relative group bg-gray-100 dark:bg-gray-800 rounded-xl p-2';
    item.dataset.fileIndex = String(index);

    const isImage = file.type.startsWith('image/');
    
    if (isImage) {
      const reader = new FileReader();
      reader.onload = (e) => {
        item.innerHTML = `
          <img src="${e.target?.result}" class="w-full h-24 object-cover rounded-lg" alt="${file.name}">
          <button type="button" data-remove="${index}" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
          <p class="text-xs text-gray-500 truncate mt-1">${file.name}</p>
        `;
      };
      reader.readAsDataURL(file);
    } else {
      item.innerHTML = `
        <div class="w-full h-24 flex items-center justify-center">
          <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </div>
        <button type="button" data-remove="${index}" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <p class="text-xs text-gray-500 truncate mt-1">${file.name}</p>
      `;
    }

    // Remove button listener
    item.querySelector('[data-remove]')?.addEventListener('click', (e) => {
      e.stopPropagation();
      this.remove(index);
    });

    this.#preview.appendChild(item);
  }

  files(): File[] {
    return [...this.#files];
  }

  clear(): this {
    this.#files = [];
    if (this.#preview) this.#preview.innerHTML = '';
    this.#input.value = '';
    emit(this.#el, 'upload:cleared', {});
    return this;
  }

  async upload(): Promise<void> {
    // This is a placeholder - actual upload logic would be implemented by the user
    emit(this.#el, 'upload:start', { files: this.#files });
  }

  remove(index: number): this {
    const removed = this.#files.splice(index, 1)[0];
    this.#preview?.querySelector(`[data-file-index="${index}"]`)?.remove();
    
    // Re-index remaining items
    this.#preview?.querySelectorAll('[data-file-index]').forEach((el, i) => {
      (el as HTMLElement).dataset.fileIndex = String(i);
      el.querySelector('[data-remove]')?.setAttribute('data-remove', String(i));
    });
    
    emit(this.#el, 'upload:file-removed', { file: removed, index });
    return this;
  }

  destroy(): void {
    instances.delete(this.#el);
  }
}
