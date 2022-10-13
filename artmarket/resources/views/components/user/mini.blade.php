<div class="flex flex-wrap">
    <div class="flex-shrink-0">
        <x-user.avatar :src="$user->profile->getAvatarSrc()"/>
    </div>
    <div class="min-w-0 ml-3 flex-1">
        <p class="font-medium">
            <a href="{{route('user.profile', ['user' =>$user->name])}}" class="hover:underline"><?=$user->name?></a>
            <x-reviews-line :max="5" :current="3" :rating="4.32" :total="143"/>
        </p>
    </div>
</div>
