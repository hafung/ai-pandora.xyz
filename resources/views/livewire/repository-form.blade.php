<div class="p-6 bg-base-200 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4 text-primary">文章生成器</h1>

    <form wire:submit.prevent="saveRepository" class="space-y-5">
        <div class="form-control">
            <label class="label">
                <span class="label-text font-medium">文章类型</span>
            </label>
            <select wire:model="type" class="select select-bordered w-full">
                <option value="github">GitHub推文</option>
                <option value="news">新闻文章</option>
{{--                <option value="quotes">毒鸡汤</option>--}}
                <option value="toxic_chicken_soup">毒鸡汤</option>
{{--                <option value="stories">都市情感</option>--}}
                <option value="urban_romance">都市情感</option>
                <option value="micro_fiction">微小说</option>
            </select>
        </div>

        @if ($type === 'github')
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">GitHub 链接</span>
                </label>
                <input wire:model.defer="url" type="url" placeholder="https://github.com/..." class="input input-bordered w-full focus:input-primary" />
            </div>
        @endif

        @if ($type !== 'github')
        <div class="form-control">
            <label class="label">
                <span class="label-text font-medium">内容</span>
            </label>
            <textarea wire:model.defer="content" placeholder="请输入内容...可以是资讯概要、一句话灵感、主题等等" class="textarea textarea-bordered w-full min-h-32 focus:textarea-primary" rows="5"></textarea>
        </div>
        @endif

{{--        <div class="form-control">--}}
{{--            <label class="label cursor-pointer justify-start gap-3">--}}
{{--                <span class="label-text font-medium">发布状态</span>--}}
{{--                <input wire:model="publishImmediately" type="checkbox" class="toggle toggle-primary" />--}}
{{--                <span class="label-text">{{ $publishImmediately ? '立即发布' : '保存到草稿箱' }}</span>--}}
{{--            </label>--}}
{{--        </div>--}}
        <div class="form-control">
            <label class="label">
                <span class="label-text font-medium">发布状态</span>
            </label>
            <div class="flex gap-4">
                <label class="label cursor-pointer justify-start gap-2">
                    <input wire:model="publishImmediately" type="radio" name="publish-status" class="radio radio-primary" value="1" />
                    <span class="label-text">立即发布</span>
                </label>
                <label class="label cursor-pointer justify-start gap-2">
                    <input wire:model="publishImmediately" type="radio" name="publish-status" class="radio radio-primary" value="0" />
                    <span class="label-text">保存到草稿箱</span>
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block"
                wire:loading.attr="disabled">
            <span wire:loading.remove>保存</span>
            <span wire:loading class="inline-flex items-center gap-2">
                <span class="loading loading-spinner loading-sm"></span>
                正在保存...
            </span>
        </button>
    </form>
</div>

