<div x-data="{tags: [], newTag: '' }" {{ $attributes }}>
    <template x-for="tag in tags">
        <input type="hidden" :value="tag" name="tags">
    </template>

    <div class="max-w-sm w-full ">
        <div class="tags-input">
            <template x-for="tag in tags" :key="tag">
                <span class="tags-input-tag">
                    <span x-text="tag"></span>
                    <button type="button" class="tags-input-remove" @click="tags = tags.filter(i => i !== tag)">
                        &times;
                    </button>
                </span>
            </template>

            <input class="tags-input-text border-gray-700 w-full" placeholder="Add tag..."
                   @keydown.enter.prevent="if (newTag.trim() !== '') tags.push(newTag.trim()); newTag = ''"
                   @keydown.backspace="if (newTag.trim() === '') tags.pop()"
                   x-model="newTag"
            >
        </div>
    </div>
</div>
