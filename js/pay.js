
$(function()
{
	window.checkChangeAlert = function(elm,oldCost,oldState){
		var nowCost = $("input[type=text][name=cost]",elm).val();
		var nowState = $("input[type=radio][name=state]:checked",elm).val();

		var dCost = 0;

		if( oldState != nowState ) //�X�e�[�^�X���ύX����Ă���ꍇ
		{
			switch( nowState ) //���݂̃X�e�[�^�X�ŕ���
			{
				case '2': //�F��
				{
					dCost = nowCost;
				}
				break;
				case '1': //���F��
				{
					dCost = -1*oldCost;
				}
				break;
			}
		}
		else //�X�e�[�^�X���ύX����Ă��Ȃ��ꍇ
		{
			if( 2 == oldState ) //�X�e�[�^�X���F�؂̏ꍇ
			{
				if( oldCost != nowCost ) //��V�ɕω����������ꍇ�B
					dCost = nowCost - oldCost;
			}
		}
		if( dCost != 0 )
		{
			if( !confirm( "�Y���̃A�t�B���G�C�^�[�ɑ΂��A"+Math.abs(dCost)+"�~�̕�V��"+((dCost>=0)?'���Z':'���Z')+"����܂��B" ) )
			{
				return false;
			}
		}

		return true;
	}
});