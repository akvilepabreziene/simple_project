<?php


namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class UserType
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isNew = $options['new'];
        $constraints = $isNew ? [new NotBlank()] : [];

        $builder
            ->add('name', TextType::class, [
                'label' => 'Vardas',
                'required' => false,
                'constraints' => [new NotBlank()]
            ])
            ->add('email', EmailType::class, [
                'label' => 'El. paštas',
                'required' => false,
                'constraints' => [new NotBlank()]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'invalid_message' => 'Slaptažodis nesutampa',
                'first_options' => ['label' => 'Slaptažodis'],
                'second_options' => ['label' => 'Pakartokite slaptažodį'],
                'constraints' => $constraints,
                'mapped' => $isNew
            ])
            ->add('save', SubmitType::class, ['label' => 'Saugoti']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'new' => false
        ]);
    }
}