<?php
namespace ItraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ItraBundle\Validator\Constraints as AcmeAssert;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="ItraBundle\Entity\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", fetch="EAGER")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(name="description", type="text", length=1000, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(name="date_create", type="date", length=100)
     */
    private $dateCreate;

    /**
     * @ORM\Column(name="date_update",type="date", length=100, nullable=true)
     */
    private $dateUpdate;

    /**
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Please, upload the image product as a JPG file.")
     * @Assert\File(mimeTypes={"image/jpeg", "image/png", "image/jpg", "image/gif"})
     */
    private $image;


    /**
     * @ORM\Column(name="sku", type="string", length=100)
     */
    private $sku;

    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="myRelation")
     */
    private $relationWithMe;

    /**
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="relationWithMe")
     * @ORM\JoinTable(name="relations",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="relation_product_id", referencedColumnName="id")}
     *      )
     * @AcmeAssert\LimitRelationBetweenProduct
     */
    private $myRelation;

    public function __construct() {
        $this->relationWithMe = new \Doctrine\Common\Collections\ArrayCollection();
        $this->myRelation = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * @param mixed $dateCreate
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;
    }

    /**
     * @return mixed
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * @param mixed $dateUpdate
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param mixed $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return mixed
     */
    public function getRelationWithMe()
    {
        return $this->relationWithMe;
    }

    /**
     * @param mixed $relationWithMe
     */
    public function setRelationWithMe($relationWithMe)
    {
        $this->relationWithMe = $relationWithMe;
    }

    /**
     * @return mixed
     */
    public function getMyRelation()
    {
        return $this->myRelation;
    }

    /**
     * @param mixed $myRelation
     */
    public function setMyRelation($myRelation)
    {
        $this->myRelation = $myRelation;
    }
  }