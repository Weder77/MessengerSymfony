<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="groupe")
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 */
class Group
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    private $file;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="groups")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="groups_admin")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users_admin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="group_send", orphanRemoval=true)
     */
    private $messages;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    public function getUsersAdmin(): ?User
    {
        return $this->users_admin;
    }

    public function setUsersAdmin(?User $users_admin): self
    {
        $this->users_admin = $users_admin;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setGroupSend($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getGroupSend() === $this) {
                $message->setGroupSend(null);
            }
        }

        return $this;
    }


    public function getFile(){
        return $this ->file;
    }
    public function setFile(UploadedFile $file){
        $this ->file = $file;
        return $this;
    }
    public function uploadFile(){
        $name = $this ->file -> getClientOriginalName();
        $newName = $this ->renameFile($name);
        // on enregistre la photo dans la bdd
        $this ->picture = $newName;
        // on enregistrela photo sur le serveur
        $this->file->move($this->dirPhoto(), $newName);
    }
    // public function removeFile(){
    //     if(file_exists($this ->dirPhoto() . $this->picture)){
    //         unlink($this ->dirPhoto() . $this->picture);
    //     }
    // }
    public function renameFile($name){
        return 'photo_' . time() . rand(1, 99999) . '_' . $name;
    }

    public function dirPhoto(){
        return __DIR__ . '/../../public/photo/';
    }


}
