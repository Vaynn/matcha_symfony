<?php
namespace App\Service;
use App\Entity\Like;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;


class WebSocketService implements MessageComponentInterface {
    protected $connections;

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {

        $this->connections = new SplObjectStorage();
        $this->manager = $manager;

    }

    function onOpen(ConnectionInterface $conn)
    {
            $this->connections->attach($conn, ['userId' => null]);
            echo(' user connecteed ');
    }

    function onClose(ConnectionInterface $conn)
    {
        echo(' close ');
        $this->connections->detach($conn);
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo($e->getMessage());
    }

    function onMessage(ConnectionInterface $from, $msg)
    {
        $message = json_decode($msg);
        echo 'Message received: ' . $msg . PHP_EOL;
        switch($message->type){
            case 'setUserId':
                $this->connections->offsetSet($from, ['userId' => $message->userId]);
                echo(' user Id = ' . $this->connections[$from]['userId']);
                break;
            case 'updateCrushStatus':
                if ($message->status === 'disliked'){
                    $this->dislike($from, $message->crushId, $message->userId);
                } elseif ($message->status === 'liked'){
                    $this->like($from, $message->crushId, $message->userId);
                }
                break;
        }
    }

    private function dislike(ConnectionInterface $from, $crushId, $userId){
        echo(' dislike');
        $crush = $this->manager->getRepository(User::class)->findOneBy(['id' => $crushId]);
        echo(' crush ' . $crush->getUsername());
        $user = $this->manager->getRepository(User::class)->findOneBy(['username' => $userId]);
        echo( ' username ' . $userId);
        $like = $this->manager->getRepository(Like::class)->findOneBy(['likedBy' => $user, 'isLiked' => $crush]);
        echo($like->getId());
        $this->manager->remove($like);
        $this->manager->flush();
    }

    private function like(ConnectionInterface $from, $crushId, $userId){
        echo(' like ');
        $crush = $this->manager->getRepository(User::class)->findOneBy(['id' => $crushId]);
        echo(' crush ' . $crush->getUsername());
        $user = $this->manager->getRepository(User::class)->findOneBy(['username' => $userId]);
        echo( ' username ' . $userId);
        $like = new Like();
        $like->setLikedAt(new \DateTimeImmutable());
        $like->setIsLiked($crush);
        $like->setLikedBy($user);
        $this->manager->persist($like);
        $this->manager->flush();
        $crush->setHaveNewNotif(true);
        $this->manager->persist($crush);
        $this->manager->flush();
        foreach ($this->connections as $connection){
            if ($this->connections->offsetGet($connection)['userId'] === $crush->getUsername()){
                $msg = ['type' => 'updateCrushStatus', 'status' => 'like'];
                $connection->send(json_encode($msg));
            }
        }

    }

}