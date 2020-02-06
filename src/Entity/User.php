<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;
    

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture; // -> BDD

    private $file; // correspond au fichier envoyé dans le formulaire (pas besoin d'être mappé) -> Form


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Group", mappedBy="users")
     */
    private $groups;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Group", mappedBy="users_admin", orphanRemoval=true)
     */
    private $groups_admin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="user", orphanRemoval=true)
     */
    private $messages;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->groups_admin = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addUser($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
            $group->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroupsAdmin(): Collection
    {
        return $this->groups_admin;
    }

    public function addGroupsAdmin(Group $groupsAdmin): self
    {
        if (!$this->groups_admin->contains($groupsAdmin)) {
            $this->groups_admin[] = $groupsAdmin;
            $groupsAdmin->setUsersAdmin($this);
        }

        return $this;
    }

    public function removeGroupsAdmin(Group $groupsAdmin): self
    {
        if ($this->groups_admin->contains($groupsAdmin)) {
            $this->groups_admin->removeElement($groupsAdmin);
            // set the owning side to null (unless already changed)
            if ($groupsAdmin->getUsersAdmin() === $this) {
                $groupsAdmin->setUsersAdmin(null);
            }
        }

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
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
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
