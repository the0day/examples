<?php

namespace App\Http\Livewire;

use App\Enums\MediaCollectionType;
use App\Models\Message;
use App\Models\Order;
use App\Models\User;
use App\Notifications\ChatMessageReceived;
use App\Services\MediaService;
use Auth;
use Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Notification;

/**
 * App\Http\Livewire\OrderChat
 *
 * @property User $user
 * @property Order $order
 * @property Message[] $messages
 */
class OrderChat extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $skip = 0;
    private $limitPerPage = 20;

    public $isMore = false;
    public $order;
    public $user;
    public $messages = [];
    public $text;
    public $images;
    public $receiverUser;

    public function render()
    {
        return view('livewire.order-chat');
    }

    public function getListeners()
    {
        return [
            // "echo-private:chat.{$this->order->id},new-message" => 'notifyNewMessage',
        ];
    }

    public function notifyNewMessage()
    {
        /*$this->messages = Message::make([
            'body' => 'qwewqe'
        ]);*/
    }

    public function loadMore()
    {
        $this->skip += $this->limitPerPage;
        $this->loadMessages();
    }

    public function mount(Order $order)
    {
        if (!Gate::allows('view', $order)) {
            abort(403);
        }

        $order->load('seller.profile', 'buyer.profile');
        $this->order = $order;
        $this->user = Auth::user();
        $this->loadMessages();
        $this->receiverUser = $this->getReceiverUser()->toChatArray();
    }

    private function loadMessages()
    {
        $messages = Message::with('user')
            ->latest()
            ->skip($this->skip)
            ->take($this->limitPerPage)
            ->get()
            ->keyBy('id');

        if ($messages->isEmpty()) {
            return;
        }

        $first = Message::oldest()->first();
        $this->isMore = $messages->last()->id != $first->id;

        $this->messages = array_replace($messages
            ->reverse()
            ->toArray(), $this->messages);
    }

    public function reply()
    {
        $this->validateSendMessage();

        $message = $this->order->messages()->create([
            'body'    => $this->text,
            'user_id' => $this->user->id
        ]);

        if ($this->images) {
            foreach ($this->images as $upload) {
                $file = TemporaryUploadedFile::createFromLivewire($upload->getFilename());
                MediaService::attach($message, $this->user, $file, MediaCollectionType::message());
            }
        }

        $this->messageAdded($message);
    }

    private function validateSendMessage(): void
    {
        if (!Gate::allows('view', $this->order)) {
            abort(403);
        }

        $this->validate([
            'text'     => 'required|min:3',
            'images.*' => 'image|max:1024',
        ]);
    }

    public function messageAdded(Message $message)
    {
        $this->messages[$message->id] = $message->toArray();
        Notification::send($this->getReceiverUser(), new ChatMessageReceived($message));
    }

    private function getReceiverUser(): User
    {
        if ($this->order->seller_id == $this->user->id) {
            return $this->order->buyer;
        }

        return $this->order->seller;
    }

    /**
     * @param $message
     */
    public function incomingMessage($message)
    {
        /*$message = Message::with('user')->find($message['id']);

        array_push($this->messages, $message);*/
    }
}
