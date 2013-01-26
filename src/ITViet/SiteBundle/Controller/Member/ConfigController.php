<?php

namespace ITViet\SiteBundle\Controller\Member;

use ITViet\SiteBundle\Controller\BaseController;
use ITViet\SiteBundle\Entity\Member;
use ITViet\SiteBundle\Form\Type\MemberEditType;
use Symfony\Component\Form\FormError;

class ConfigController extends BaseController
{
    public function editInfoAction() {
        $t = $this->get('translator');
        $request = $this->getRequest();
        $em = $this->get('doctrine.orm.entity_manager');
        $member = $this->get('security.context')->getToken()->getUser();

        $form = $this->createForm(new MemberEditType(), $member);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em->persist($member);
                $em->flush();
                $this->get('session')->setFlash('success', $t->trans('Your account information has been updated successfully.'));
                return $this->redirect($this->generateMlUrl('_member_home'));
            }
        }

        $res = $this->render('ITVietSiteBundle:Member\\Config:editInfo.html.twig', array(
          'form' => $form->createView(),
        ));
        $res->setSharedMaxAge(30);
        $res->setPublic();
        return $res;
    }

    public function editEmailAction() {
        $t = $this->get('translator');
        $session = $this->get('session');
        $request = $this->getRequest();
        $em = $this->get('doctrine.orm.entity_manager');
        $member = $this->get('security.context')->getToken()->getUser();

        if ($request->getMethod() == 'POST') {
            $password = $request->get('password');
            if (! $member->isCorrectPassword($password)) {
                $session->setFlash('error',$t->trans('Your current password is not correct'));
                return $this->redirect($this->generateMlUrl('_member_home'));
            } else {
                //check email uniqueness
                $newEmail = $request->get('member_newemail');
                $samePerson = $em->getRepository("ITVietSiteBundle:Member")
                  ->findBy(array('email' => $newEmail));
                if (sizeof($samePerson) > 0) {
                    $session->setFlash('error',$t->trans('Your email already exist'));
                    return $this->redirect($this->generateMlUrl('_member_home'));
                } else {
                    $member->generateConfirmationToken();
                    $member->setEmailChangeRequestedAt(new \DateTime());
                    $member->setEmailTemporary($newEmail);

                    //send mail
                    $message = \Swift_Message::newInstance()
                      ->setSubject($t->trans('Change Email On ITViet'))
                      ->setFrom($this->container->getParameter('mail'))
                      ->setTo($newEmail)
                      ->setBody($this->renderView(
                        'ITVietSiteBundle:Mail:change_email.html.twig',array(
                          'member' => $member,
                        )
                      ), 'text/html');
                    $this->get('mailer')->send($message);

                    $em->persist($member);
                    $em->flush();
                    $session->setFlash('success',$t->trans("Please check your new email {$newEmail} to approve your change"));
                    return $this->redirect($this->generateMlUrl('_member_home'));
                }
            }

        }

        $res = $this->render('ITVietSiteBundle:Member\\Config:editEmail.html.twig', array(
          'member' => $member,
        ));
        $res->setSharedMaxAge(30);
        $res->setPublic();
        return $res;
    }


    public function changeEmailAction($token) {
        $em = $this->get('doctrine.orm.entity_manager');
        $t = $this->get('translator');

        if ($member = $em->getRepository("ITVietSiteBundle:Member")->findOneBy(array('confirmationToken' => $token))) {
            $emailTemporary = $member->getEmailTemporary();
            if(! $emailTemporary)
                throw new \Exception('The new email is not set');

            $member->setEmailTemporary(null);
            $member->setEmail($emailTemporary);

            $em->persist($member);
            $em->flush();
            $this->get('session')->setFlash('success',$t->trans('Your account information has been updated successfully.'));
            return $this->redirect($this->generateMlUrl('_member_login'));

            //logout old account
            //$this->get('request')->getSession()->invalidate();
            //$this->get('security.context')->setToken(null);

        }

        return $this->redirect($this->generateMlUrl('_member_login'));
    }

    public function editPasswordAction() {
        $t = $this->get('translator');
        $request = $this->getRequest();
        $session = $this->get('session');
        $em = $this->get('doctrine.orm.entity_manager');
        $member = $this->get('security.context')->getToken()->getUser();

        if ($request->getMethod() == 'POST') {
            $password = $request->request->get('old_password');
            if (! $member->isCorrectPassword($password)) {
                $session->setFlash('error', $t->trans('Your Old password is not match'));
                return $this->redirect($this->generateMlUrl('_member_home'));
            } else {
                $newPassword = $request->request->get('new_password');
                $reNewPassword = $request->request->get('re_new_password');

                if ($newPassword) {
                    if ($newPassword == $reNewPassword) {
                        $member->setPassword($newPassword);
                        $em->persist($member);
                        $em->flush();

                        $jumpTo = $this->generateMlUrl('_member_login');

                        //let user log-out
                        $request->getSession()->invalidate();
                        $this->get('security.context')->setToken(null);

                        $session->setFlash('success', $t->trans('Your account information has been updated successfully.'));
                        return $this->redirect($jumpTo);

                    } else {
                        $session->setFlash('error', $t->trans('Your new password does not match. Please re-enter'));
                    }
                } else {
                    throw new \Exception('Empty form');
                }
            }

        }

        $res = $this->render('ITVietSiteBundle:Member\\Config:editPassword.html.twig');
        $res->setSharedMaxAge(30);
        $res->setPublic();
        return $res;
    }
}
