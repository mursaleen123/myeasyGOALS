<?php

namespace Database\Seeders;

use App\Models\Faqs;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faqs = [
            [
                'question' => 'What is the My Easy Goals™ System?',
                'answer' => 'The My Easy Goals™ system is a powerful new goal achievement technology that allows your mind to release any unconscious blocks, so you can achieve any goal or objective you desire. It does this via a cutting-edge audio conditioning process in less than 60 seconds.',
                'is_active' => '1',
            ],
            [
                'question' => 'How Does My Easy Goals™ work?',
                'answer' => 'In essence My Easy Goals™ system guides your mind to release all of the unconscious reasons why you resist achieving a particular goal or objective and implementing the action steps.
                It does this via a cutting-edge audio conditioning process.',
                'is_active' => '1',
            ],
            [
                'question' => 'What will I experience?',
                'answer' => 'After you’ve listened to the 60 second audio, you’ll experience a subtle mental shift after which your perspective on your goal will change and even your attitudes to the action steps that you have to implement in order to make it happen.',
                'is_active' => '1',
            ],
            [
                'question' => 'Why Is Goal Setting Is So Hard?',
                'answer' => 'Through a combination of negative experiences and poor choices many people have built up an inner resistance to achieving goals and activities.
                This can manifest as procrastination, anxiety and a whole host of self-sabotaging behaviours.',
                'is_active' => '1',
            ],
            [
                'question' => 'What\'s in The Audio?',
                'answer' => 'The Neuro-Accelerated Conditioning contains only positive instructions aimed at guiding your mind to release any unconscious blocks which may be keeping you from achieving the goal or objective you want to achieve. It’s done by using a unique layering process and wording.
                You can view the suggestions contained in the audio below:
                I am talking to the unconscious mind of the person listening to this recording who has just typed their goal or objective into the "Goal box" from within The My Easy Goals™ app.
                Unconscious mind please RELEASE all of the thoughts, beliefs and fears that are causing you to resist easily achieving the last goal or objective that was typed into the Goal Box and implementing ALL of the action steps listed to achieve it.
                Unconscious mind please only work on those goals or objectives that are not for the purpose of physically harming any human being and or damaging their property, which are on the behalf of an individual who is over 18 years of age.',
                'is_active' => '1',
            ],
            [
                'question' => 'Can I Harm Myself?',
                'answer' => 'No! This process only works if the purpose of your goals are NOT to harm either yourself or another human being. This has been reinforced by additional commands inserted into the audio.',
                'is_active' => '1',
            ],
            [
                'question' => 'What Kind of Goals Can I Work On?',
                'answer' => 'You can work on any goals you desire, however please ensure that these are goals that you truly want to achieve that are measurable and realistic.',
                'is_active' => '1',
            ],
            [
                'question' => 'How Many Goals Can I Work On?',
                'answer' => 'We would suggest that for long-term goals you keep your list to 5 goals at any one time.
                For incidental goals not connected to your long-term aims, such as upcoming events or resolving specific issues, or simple to do items that you’ve previously been putting off you can use the My Easy Goals™ system as often as you like.',
                'is_active' => '1',
            ],
            [
                'question' => 'How Do I Cancel My Membership?',
                'answer' => 'To cancel your membership simply go to link.',
                'is_active' => '1',
            ],
        ];
        foreach ($faqs as $faq) {
            Faqs::create($faq);
        }
    }
}
