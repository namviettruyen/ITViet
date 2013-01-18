<?php

namespace ITViet\SiteBundle\Controller;

use ITViet\SiteBundle\Controller\BaseController;
use ITViet\SiteBundle\Entity\Member;
use ITViet\SiteBundle\Entity\MemberLoginInfo;
use ITViet\SiteBundle\Form\Type\MemberRegisterType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RegisterController extends BaseController
{
    /**
     * @Template()
     */
    public function newAction(Request $request) {

        $t = $this->get('translator');
        $session = $this->get('session');
        $em = $this->get('doctrine.orm.entity_manager');
        $member = new Member();

        $form = $this->createForm(new MemberRegisterType(), $member);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            //check email uniqueness
            $email = $form['email']->getData();
            $samePerson = $em->getRepository('ITVietSiteBundle:Member')
              ->findBy(array('email' => $email));
            if (sizeof($samePerson) > 0)
                $form->addError(new FormError($t->trans('This email address was used.')));

            //check valid password
            if (strlen($form['password']->getData()) < 6)
                $form->addError(new FormError($t->trans('Your password is too short')));
            if ($form['password']->getData() != $form['retypePassword']->getData())
                $form->addError(new FormError($t->trans('Your retype password is not match')));

            if($form->isValid()){
                $member->setSince(new \DateTime());
                $member->generateConfirmationToken();
                $loginInfo = new MemberLoginInfo();
                $loginInfo->setCount(0);
                $member->setLoginInfo($loginInfo);
                $member->updateMetaInfo();
                $email = $member->getEmail();

                //send mail
                $message = \Swift_Message::newInstance()
                  ->setSubject($t->trans('Your Register With ITViet'))
                  ->setFrom($this->container->getParameter('mail'))
                  ->setTo($email)
                  ->setBody($this->renderView(
                    'ITVietSiteBundle:Mail:confirmation.html.twig',array(
                      'member' => $member,
                    )
                  ), 'text/html');
                $this->get('mailer')->send($message);

                $em->persist($member);
                $em->flush();

                $session->set('member_confirmation_email', $member->getEmail());
                return $this->redirect($this->generateMlUrl('_member_check_confirm_email'));
            }
        }

        return array(
          'form' => $form->createView(),
        );
    }

    public function checkEmailAvailabilityAction($email) {
        $em = $this->get('doctrine.orm.entity_manager');
        $samePerson = $em->getRepository('ITVietSiteBundle:Member')->findBy(array('email' => $email));
        $isAvailable = sizeof($samePerson) > 0 ? false : true;

        $res = $this->render(
          'ITVietSiteBundle:Register:checkEmailAvailability.txt.twig', array(
            'email' => $email,
            'isAvailable' => $isAvailable,
          )
        );
        $res->headers->set('Content-Type', 'text/plain');
        return $res;
    }
    /**
     * @Template()
     */
    public function checkConfirmEmailAction(){
        $session = $this->get('session');
        if ($email = $session->get('member_confirmation_email')) {
            $session->remove('member_confirmation_email');
        }
        return array(
          'email' => $email,
        );
    }

    public function confirmAction($token){
        $em = $this->get('doctrine.orm.entity_manager');
        $member = $em->getRepository('ITVietSiteBundle:Member')->findOneBy(array('confirmationToken'=>$token));
        if ($member) {
            if ($member->isEnabled())
                return $this->redirect($this->generateMlUrl('_member_already_confirmed'));

            $member->setEnabled(true);

            $em->persist($member);
            $em->flush();

            return $this->redirect($this->generateMlUrl('_member_confirmed'));
        } else {
            throw $this->createNotFoundException('The comfirm code not exists !');
        }
    }

    /**
     * @Template()
     */
    public function confirmedAction(){
        return array();
    }

    /**
     * @Template()
     */
    public function alreadyConfirmedAction(){
        return array();
    }

}
