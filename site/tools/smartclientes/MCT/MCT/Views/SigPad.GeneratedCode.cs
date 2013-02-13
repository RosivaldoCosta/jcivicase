using System;
using System.Collections.Generic;
using System.Text;

using System;
using Microsoft.Practices.CompositeUI.SmartParts;
using Microsoft.Practices.ObjectBuilder;
using Sante.EMR.SmartClient.Infrastructure.Interface;
using Sante.EMR.SmartClient.MCT;

namespace Sante.EMR.SmartClient.MCT.Views
{
    [SmartPart]
    public partial class SigPad
    {
        /// <summary>
        /// Sets the presenter. The dependency injection system will automatically
        /// create a new presenter for you.
        /// </summary>
        [CreateNew]
        public SigPadPresenter Presenter
        {
            set
            {
                _presenter = value;
                _presenter.View = this;
            }
        }

    }
}
