<?php


namespace App\Form;


use App\Entity\Posts;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;


class PostType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('text', TextType::class)
        ->add('link', UrlType::class, ['required' => false]);

}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
        ]);
    }
}
