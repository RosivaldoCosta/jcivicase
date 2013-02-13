//----------------------------------------------------------------------------------------
// patterns & practices - Smart Client Software Factory - Guidance Package
//
// This file was generated by this guidance package as part of the solution template
//
// The IDependentModuleInfo interface is used along with the DependantModuleLoaderService.
// 
// For more information see: 
// ms-help://MS.VSCC.v80/MS.VSIPCC.v80/ms.practices.scsf.2007may/SCSF/html/03-01-010-How_to_Create_Smart_Client_Solutions.htm
//
// Latest version of this Guidance Package: http://go.microsoft.com/fwlink/?LinkId=62182
//----------------------------------------------------------------------------------------

using System;
using System.Collections.Generic;
using System.Text;
using Microsoft.Practices.CompositeUI.Configuration;

namespace Sante.EMR.SmartClient.Infrastructure.Library.Services
{
    public interface IDependentModuleInfo : IModuleInfo
    {
        IList<string> Dependencies { get; }
        string Name { get; }
    }
}
